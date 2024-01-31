<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function createPaymentProof(Car $car)
    {
        return view('cars.create-payment-proof', compact('car'));
    }

    public function storePaymentProof(Request $request, Car $car)
    {
        // Validasi request dan menyimpan bukti pembayaran pada entitas Car
        // ...

        return redirect()->route('car.index', $car->id)->with('success', 'Bukti pembayaran berhasil ditambahkan.');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $cars = Car::where('name', 'like', '%' . $search . '%')
            ->orWhere('plat', 'like', '%' . $search . '%')
            ->orWhere('price', 'like', '%' . $search . '%')
            ->latest()
            ->paginate(10);
        return view('cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cars.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  =>  'required',
            'plat'  =>  'required',
            'description'  =>  'required',
            'price'  =>  'required|integer',
            'status'  =>  'required',
        ]);
        if ($request->file('image')) {
            $data['image'] = $request->file('image')->store('cars');
        }
        Car::create($data);
        return redirect()->route('car.index')->with('success', 'Data mobil   berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        return view('cars.edit', ['car' => $car]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        $data = $request->validate([
            'name'  =>  'required',
            'plat'  =>  'required',
            'description'  =>  'required',
            'price'  =>  'required|integer',
            'status'  =>  'required',
        ]);
        if ($request->file('image')) {
            if ($request->oldImage) {
                Storage::delete($request->oldImage);
            }
            $data['image'] = $request->file('image')->store('cars');
        }
        $car->update($data);
        return redirect()->route('car.index')->with('success', 'Data mobil berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        if ($car->image) {
            Storage::delete($car->image);
        }
        Car::destroy($car->id);
        return redirect()->route('car.index')->with('success', 'Data mobil berhasil dihapus');
    }
}
