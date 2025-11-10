<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class UserPasswordController extends Controller
{
    // ====================== GANTI PASSWORD (USER LOGIN) ======================
    public function edit()
    {
        return view('auth.reset-password'); // resources/views/auth/reset-password.blade.php
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Password berhasil diperbarui.');
    }

    // ====================== LUPA PASSWORD VIA EMAIL (OTP) ======================
    public function forgotPasswordForm()
    {
        return view('auth.forgot-password'); 
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate OTP 6 digit
        $otp = rand(100000, 999999);

        // Simpan ke session
        session([
            'password_reset_email'   => $user->email,
            'password_reset_otp'     => $otp,
            'password_reset_expires' => now()->addMinutes(5),
        ]);

        // Kirim email via Blade
        Mail::send('auth.emails.otp', [
            'user' => $user,
            'otp'  => $otp
        ], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Reset Password Quba Barbershop');
        });

        return redirect()->route('user.password.verifyOtpForm')
                         ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function verifyOtpForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $otpSession = session('password_reset_otp');
        $expires    = session('password_reset_expires');

        if (!$otpSession) {
            return redirect()->route('user.password.request')
                             ->withErrors(['otp' => 'OTP tidak ditemukan.']);
        }

        if (now()->gt($expires)) {
            session()->forget(['password_reset_email', 'password_reset_otp', 'password_reset_verified', 'password_reset_expires']);
            return redirect()->route('user.password.request')
                             ->withErrors(['otp' => 'Kode OTP telah kadaluarsa.']);
        }

        if ($request->otp != $otpSession) {
            return back()->withErrors(['otp' => 'Kode OTP salah']);
        }

        // Tandai OTP valid
        session(['password_reset_verified' => true]);

        return redirect()->route('user.password.resetForm');
    }

    public function resetPasswordForm()
    {
        if (!session('password_reset_verified')) {
            return redirect()->route('user.password.request')
                             ->withErrors(['email' => 'OTP belum diverifikasi']);
        }

        return view('auth.reset-password-new');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $email = session('password_reset_email');
        if (!$email) {
            return redirect()->route('user.password.request')
                             ->withErrors(['email' => 'Email tidak ditemukan']);
        }

        $user = User::where('email', $email)->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear semua session OTP
        session()->forget([
            'password_reset_email',
            'password_reset_otp',
            'password_reset_verified',
            'password_reset_expires'
        ]);

        return redirect()->route('user.login.form')
                         ->with('success', 'Password berhasil diubah. Silakan login.');
    }
}
