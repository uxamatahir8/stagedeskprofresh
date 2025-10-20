<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
</head>

<body>
    <h2>Hello {{ $user->name ?? 'User' }},</h2>

    <p>We received a request to reset your password.</p>
    <p>Click the button below to reset your password:</p>

    <p>
        <a href="{!! $resetLink !!}"
            style="background:#4CAF50;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;">
            Reset Password
        </a>
    </p>

    <p>If you didn’t request a password reset, please ignore this email.</p>

    <p>Regards,<br>Support Team</p>
</body>

</html>