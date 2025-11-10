<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan halaman register user
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Proses penyimpanan data user baru
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'no_hp'    => ['nullable', 'regex:/^[0-9+\-]+$/', 'max:20'],
            'alamat'   => ['nullable', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'nama'     => $request->nama,
            'username' => $request->username,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
            'alamat'   => $request->alamat,
            'password' => Hash::make($request->password),
            'role'     => 'user', // default hanya user
        ]);

        event(new Registered($user));

        // Setelah register, arahkan ke login form
        return redirect()
            ->route('user.login.form')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
