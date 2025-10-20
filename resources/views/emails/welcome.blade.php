<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Welcome to StageDesk Pro</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-wrapper {
            width: 100%;
            background-color: #f8f9fa;
            padding: 40px 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .email-header {
            background-color: #0d6efd;
            color: #fff;
            text-align: center;
            padding: 25px 20px;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .email-body {
            padding: 30px;
            line-height: 1.6;
        }

        .email-body h2 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #0d6efd;
        }

        .email-body p {
            margin: 10px 0;
        }

        .cta-button {
            display: inline-block;
            background-color: #0d6efd;
            color: #fff;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: 500;
            margin-top: 20px;
        }

        .cta-button:hover {
            background-color: #0b5ed7;
        }

        .email-footer {
            text-align: center;
            font-size: 13px;
            color: #777;
            padding: 20px;
            border-top: 1px solid #eee;
        }

        @media (max-width: 600px) {
            .email-body {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                <h1>Welcome to StageDesk Pro 🎉</h1>
            </div>

            <div class="email-body">
                <h2>Hello {{ $user->name }},</h2>

                <p>Thank you for registering as a <strong>{{ ucfirst($user->role ?? 'user') }}</strong> on
                    <strong>StageDesk Pro</strong>.
                </p>

                <p>We’re excited to have you on board! You can now log in to your account and explore the features we’ve
                    built for you.</p>

                <p style="text-align:center;">
                    <a href="{{ url('/login') }}" class="cta-button">Go to Login</a>
                </p>

                <p>If you didn’t create this account, please ignore this email.</p>

                <p>Welcome once again and have a great day!</p>
            </div>

            <div class="email-footer">
                &copy; {{ date('Y') }} StageDesk Pro. All rights reserved.<br>
                <a href="{{ url('/') }}" style="color:#0d6efd; text-decoration:none;">Visit our website</a>
            </div>
        </div>
    </div>
</body>

</html>