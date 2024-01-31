<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // app/Http/Controllers/TransactionController.php


    public function showPaymentProof(Transaction $transaction)
    {
        return view('transactions.show-payment-proof', compact('transaction'));
    }

    public function createPaymentProof()
    {
        // Ambil daftar transaksi yang bisa dipilih
        $transactions = Transaction::all();

        return view('transaction.create-payment-proof', compact('transactions'));
    }

    public function storePaymentProof(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $transaction = Transaction::find($request->transaction_id);

        if ($transaction) {
            // Simpan bukti pembayaran
            if ($request->hasFile('payment_proof')) {
                $paymentProof = $request->file('payment_proof');
                $paymentProofPath = $paymentProof->store('payment_proofs');
                $transaction->update(['payment_proof' => $paymentProofPath]);
            }

            return redirect()->route('transaction.show', $transaction->id)->with('success', 'Bukti pembayaran berhasil ditambahkan.');
        }

        // Handle jika transaksi tidak ditemukan
        return redirect()->route('transaction.createPaymentProof')->with('error', 'Transaksi tidak ditemukan.');
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $user = auth()->user();

            $transactions = $user->is_admin
                ? Transaction::latest()->get()
                : $user->transactions()->latest()->get();
            return DataTables::of($transactions)
                ->addIndexColumn()
                
                ->editColumn('user_id', function (Transaction $transaction) {
                    // Pastikan model Transaction sudah dimuat dengan relasi user
                    $transaction->load('user');
                    // Cek apakah relasi user ada dan memiliki properti email
                    return optional($transaction->user)->name;
                })
                ->editColumn('car_id', function (Transaction $transaction) {
                    $transaction = Transaction::with('car')->find($transaction->id);
                    return $transaction->car->name;
                })
                ->editColumn('date_start', function (Transaction $transaction) {
                    return Carbon::parse($transaction->date_start)->format('d F Y');
                })
                ->editColumn('date_end', function (Transaction $transaction) {
                    return Carbon::parse($transaction->date_end)->format('d F Y');
                })
                ->editColumn('total', function (Transaction $transaction) {
                    return number_format($transaction->total, 2);
                })
                ->addColumn('action', function (Transaction $transaction) {

                    $btn = '<a href=' . route("transaction.show", $transaction->id) . ' class="btn btn-info btn-sm">Bukti Bayar</a>';
                    $btn .= '&nbsp;&nbsp;'; // Menambahkan dua spasi
                    $btn .= '<a href=' . route("transaction.index") . ' class="btn btn-primary btn-sm">Transaksi</a>';


                    $btn .= (auth()->user()->is_admin == 1)
                        ? '<form class="d-inline" action=' . route("transaction.destroy", $transaction->id) . ' method="POST">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value=' . csrf_token() . '>
        <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash"></i></button>
        </form>'
                        : '';

                    $btn .= (auth()->user()->is_admin == 1)
                        ? '<a href=' . route("transaction.edit", $transaction->id) . ' class="edit btn btn-success btn-sm"><i class="fas fa-edit"></i></a>'
                        : '';
                    $btn .= (auth()->user()->is_admin == 1)
                        ? '<form class="d-inline" action=' . route("transaction.status", $transaction->id) . ' method="POST">
        <input type="hidden" name="_token" value=' . csrf_token() . '>
        <input type="hidden" name="status" value="SUCCESS">
        <button class="btn btn-outline-success btn-sm" type="submit"><i class="far fa-check-circle"></i></button>
        </form>'
                        : '';

                    $btn .= (auth()->user()->is_admin == 1)
                        ? '<form class="d-inline" action=' . route("transaction.status", $transaction->id) . ' method="POST">
        <input type="hidden" name="_token" value=' . csrf_token() . '>
        <input type="hidden" name="status" value="FAILED">
        <button class="btn btn-outline-danger btn-sm" type="submit"><i class="far fa-times-circle"></i></button>
        </form>'
                        : '';
                    $btn .= '<br>';
                    $btn .= '<img src="' . asset('storage/' . $transaction->payment_proof) . '" alt="Payment Proof" style="max-width: 100px; max-height: 100px;">';
                    return $btn;
                })
                ->rawColumns(['action', 'modal'])
                ->make(true);
        }
        $transactions = auth()->user()->transactions()->with('car')->get();
        return view('transaction.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cars = Car::all();
        $users = User::all();
        return view('transaction.create', compact('cars', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'    =>  'required',
            'car_id'    =>  'required',
            'date_start'    =>  'required',
            'date_end'    =>  'required',
            'note'    =>  'required',
        ]);
        $data['date_due'] = $request->date_end;
        $data['status'] = 'PENDING';
        $price = Car::find($request->car_id);
        $date_start = new DateTime($request->date_start);
        $date_end = new DateTime($request->date_end);
        $duration = $date_start->diff($date_end);
        $data['total'] = $price->price * $duration->days;
        Transaction::create($data);
        return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction = Transaction::with('user', 'car')->find($transaction->id);
        return view('transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $users = User::all();
        $cars = Car::all();
        return view('transaction.edit', compact('users', 'cars', 'transaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'user_id'    =>  'required',
            'car_id'    =>  'required',
            'date_start'    =>  'required',
            'date_end'    =>  'required',
            'note'    =>  'required',
        ]);
        $price = Car::find($request->car_id);
        $date_start = new DateTime($request->date_start);
        $date_end = new DateTime($request->date_end);
        $duration = $date_start->diff($date_end);
        $data['total'] = $price->price * $duration->days;
        $transaction->update($data);
        return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        Transaction::destroy($transaction->id);
        return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil dihapus');
    }

    public function status(Request $request, Transaction $transaction)
    {
        $transaction->update([
            'status'    =>  $request->status
        ]);
        return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil diupdate');
    }
}
