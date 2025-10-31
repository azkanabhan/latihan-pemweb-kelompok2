<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attendee Account Created</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; }
        .container { max-width: 560px; margin: 0 auto; padding: 24px; }
        .btn { display: inline-block; background: #4f46e5; color: #fff; padding: 10px 16px; border-radius: 8px; text-decoration: none; }
        .muted { color: #6b7280; font-size: 12px; }
        .code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; background: #f3f4f6; padding: 2px 6px; border-radius: 4px; }
    </style>
    </head>
<body>
    <div class="container">
        <h2>Welcome to our events platform</h2>
        <p>Hi {{ $user->name }},</p>
        <p>An attendee account has been created for your email. You can log in using the credentials below:</p>
        <ul>
            <li><strong>Email:</strong> {{ $user->email }}</li>
            <li><strong>Password:</strong> <span class="code">{{ $plainPassword }}</span></li>
        </ul>
        <p>Please change your password after logging in.</p>
        <p>
            <a href="{{ $loginUrl }}" class="btn">Log in</a>
        </p>
        <p class="muted">If you didn't request this account, you can ignore this email.</p>
    </div>
</body>
</html>



