<?php

namespace App\Mail;

use App\Models\PasswordOtp;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordOtpCode extends Mailable
{
    use Queueable, SerializesModels;

    public PasswordOtp $otp;

    public function __construct(PasswordOtp $otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Your Password Reset OTP')
            ->view('emails.password_otp')
            ->with([
                'email' => $this->otp->email,
                'code' => $this->otp->code,
                'expiresAt' => $this->otp->expires_at,
            ]);
    }
}




