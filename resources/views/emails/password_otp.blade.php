<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset OTP</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; }
        .container { max-width: 560px; margin: 0 auto; padding: 24px; }
        .code { font-size: 22px; letter-spacing: 4px; font-weight: bold; background: #f3f4f6; padding: 8px 12px; display: inline-block; border-radius: 8px; }
        .muted { color: #6b7280; font-size: 12px; }
    </style>
    </head>
<body>
    <div class="container">
        <h2>Password Reset OTP</h2>
        <p>Hi,</p>
        <p>Use the following one-time password to reset your password for {{ $email }}:</p>
        <p class="code">{{ $code }}</p>
        <p class="muted">This code will expire at {{ $expiresAt->timezone('Asia/Jakarta')->format('d M Y, H:i') }}.</p>
        <p>If you didn't request this, you can ignore this email.</p>
    </div>
</body>
</html>




