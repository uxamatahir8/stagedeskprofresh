<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Password Reset Successful</title>
</head>

<body>
    <h2>Hello {{ $user->name ?? 'User' }},</h2>

    <p>Your password has been successfully reset.</p>
    <p>If this was not you, please contact support immediately.</p>

    <p>Thank you,<br>Support Team</p>
</body>

</html>