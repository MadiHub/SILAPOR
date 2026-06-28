<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('Auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tidak terdaftar di sistem kami.',
        ]);

        PasswordResetOtp::where('email', $request->email)->delete();

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetOtp::create([
            'email'      => $request->email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($request->email)->send(new \App\Mail\OtpMail($otp, $request->email));

        session(['reset_email' => $request->email]);

        return redirect()->route('password.verify-otp')
            ->with('success', 'Kode OTP telah dikirim ke email Anda. Berlaku 10 menit.');
    }

    public function showOtpForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi habis. Ulangi proses.']);
        }

        $record = PasswordResetOtp::where('email', $email)
            ->where('otp', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
        }

        $record->update(['used' => true]);

        session(['reset_verified' => true]);

        return redirect()->route('password.reset-form');
    }

    public function showResetForm()
    {
        if (!session('reset_email') || !session('reset_verified')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        if (!session('reset_email') || !session('reset_verified')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        $user = User::where('email', session('reset_email'))->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'User tidak ditemukan.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        session()->forget(['reset_email', 'reset_verified']);

        return redirect()->route('auth')
            ->with('success', 'Password berhasil diubah! Silakan masuk.');
    }
}