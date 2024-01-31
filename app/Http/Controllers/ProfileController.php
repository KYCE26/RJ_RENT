<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function index()
    {
        // Logika untuk menampilkan profil
        return view('profile.index');
    }
    public function password()
    {
        return view('profile.password');
    }
    public function change_password(Request $request)
    {
        $data = $request->validate([
            'old_password'  =>  'required',
            'password'  =>  'required|confirmed',
        ]);
        if (Hash::check($request->old_password, auth()->user()->password)) {
            return redirect()->route('change-password')->with('error', 'Password lama tidak cocok');
        }
        $user = User::find(auth()->user()->id);
        $user->update([
            'password'  =>  Hash::make($request->password)
        ]);
        return redirect()->route('change-password');
    }
    public function profile()
    {
        $data = Auth::user();
        return view('profile.index', compact('data'));
    }

    public function change_profile(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $data = $request->validate([
            'name'  =>  'required',
            'email'  =>  'required|email:rfc,dns',
        ]);
        $user->update($data);

        // Kirim variabel $data ke view untuk menampilkan informasi terbaru
        return view('profile.index', compact('data'))->with('success', 'Data pelanggan berhasil diupdate');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
