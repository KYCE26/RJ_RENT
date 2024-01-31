<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Nette\Utils\Strings;
use Stringable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
        $search = $request->search;
        $users = User::where('name', 'like', '%' . $search .'%')
        ->orWhere('email', 'like', '%' . $search .'%')
        ->latest()
        ->paginate(10);
        return view('users.index', compact('users'));
        }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){
        return view('users.create');
        }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $data = $request->validate([
        'name'  =>  'required',
        'email'  =>  'required|email:rfc,dns|unique:App\Models\User,email',
        ]);
        $data['password'] = Crypt::encrypt(Str::random(8));
        if($request->file('image')){
        $data['image'] = $request->file('image')->store('cars');
        }
        User::create($data);
        return redirect()->route('user.index')->with('success', 'Data pelanggan berhasil ditambahkan');
        }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    // Mendapatkan informasi pelanggan yang sedang login
    $loggedInUser = Auth::user();

    // Sekarang Anda memiliki $loggedInUser yang dapat digunakan di sini

    return view('transaction.create', compact('loggedInUser'));
        //    // Mendapatkan informasi pelanggan yang sedang login
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user){
        return view('users.edit', compact('user'));
        }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user){
        $data = $request->validate([
        'name'  =>  'required',
        'email'  =>  'required|email:rfc,dns',
        ]);
        if($request->file('image')){
        if($request->oldImage){
        Storage::delete($request->oldImage);
        }
        $data['image'] = $request->file('image')->store('users');
        }
        $user->update($data);
        return redirect()->route('user.index')->with('success', 'Data pelanggan berhasil diupdate');
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->image) {
            Storage::delete($user->image);
        }

        // Menghapus user
        $user->delete();

        return redirect()->route('user.index')->with('success', 'Data pelanggan berhasil dihapus');
    }
}
