<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordOtpCode;
use App\Models\PasswordOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpPasswordController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.forgot-password-otp');
    }

    public function sendOtp(Request $request)
    {
        $data = $request->validate(['email' => 'required|email']);

        $user = User::where('email', $data['email'])->first();
        if (! $user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar']);
        }

        $code = (string) random_int(100000, 999999);
        PasswordOtp::where('email', $data['email'])->delete();
        $otp = PasswordOtp::create([
            'email' => $data['email'],
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($data['email'])->send(new PasswordOtpCode($otp));

        return redirect()->route('password.otp.verify.form')->with(['email' => $data['email']]);
    }

    public function showVerifyForm(Request $request)
    {
        $email = session('email');
        return view('auth.verify-otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
        ]);

        $otp = PasswordOtp::where('email', $data['email'])->latest()->first();
        if (! $otp || $otp->consumed_at || $otp->expires_at->isPast()) {
            return back()->withErrors(['code' => 'Kode OTP tidak valid atau sudah kadaluarsa']);
        }

        if (! hash_equals($otp->code, $data['code'])) {
            $otp->increment('attempts');
            return back()->withErrors(['code' => 'Kode OTP salah']);
        }

        // Mark consumed and redirect to set password form
        $otp->consumed_at = now();
        $otp->save();

        return redirect()->route('password.otp.reset.form')->with(['email' => $data['email']]);
    }

    public function showResetForm(Request $request)
    {
        $email = session('email');
        abort_unless($email, 403);
        return view('auth.reset-password-otp', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::where('email', $data['email'])->firstOrFail();
        $user->password = Hash::make($data['password']);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Cleanup OTPs for this email
        PasswordOtp::where('email', $data['email'])->delete();

        return redirect()->route('login')->with('status', 'Password berhasil diubah');
    }
}




