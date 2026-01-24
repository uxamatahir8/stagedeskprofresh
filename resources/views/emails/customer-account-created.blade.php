<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Account Has Been Created</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #4CAF50;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4CAF50;
            margin: 0;
            font-size: 28px;
        }
        .content {
            margin-bottom: 30px;
        }
        .credentials-box {
            background-color: #f8f9fa;
            border-left: 4px solid #4CAF50;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .credentials-box h3 {
            margin-top: 0;
            color: #4CAF50;
        }
        .credential-item {
            margin: 10px 0;
            padding: 10px;
            background-color: #fff;
            border-radius: 4px;
        }
        .credential-label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }
        .credential-value {
            font-size: 16px;
            color: #333;
            font-family: 'Courier New', monospace;
            background-color: #e9ecef;
            padding: 8px 12px;
            border-radius: 4px;
            display: inline-block;
        }
        .booking-info {
            background-color: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .booking-info h3 {
            margin-top: 0;
            color: #2196F3;
        }
        .info-row {
            margin: 8px 0;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .login-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .login-button:hover {
            background-color: #45a049;
        }
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .warning-box p {
            margin: 0;
            color: #856404;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #777;
            font-size: 14px;
        }
        .button-container {
            text-align: center;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎉 Welcome to StageDesk Pro!</h1>
        </div>

        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>

            <p>Great news! An account has been created for you on StageDesk Pro by <strong>{{ $booking->company->name ?? 'our team' }}</strong>. A booking has been created on your behalf for an upcoming event.</p>

            <div class="credentials-box">
                <h3>🔐 Your Login Credentials</h3>
                <p>Please use the following credentials to access your account:</p>

                <div class="credential-item">
                    <span class="credential-label">Email Address:</span>
                    <span class="credential-value">{{ $user->email }}</span>
                </div>

                <div class="credential-item">
                    <span class="credential-label">Temporary Password:</span>
                    <span class="credential-value">{{ $password }}</span>
                </div>
            </div>

            <div class="warning-box">
                <p><strong>⚠️ Important Security Note:</strong> Please change your password after your first login. You can do this from your account settings.</p>
            </div>

            <div class="booking-info">
                <h3>📅 Your Booking Details</h3>
                <div class="info-row">
                    <span class="info-label">Event Type:</span> {{ $booking->eventType->event_name ?? 'N/A' }}
                </div>
                <div class="info-row">
                    <span class="info-label">Event Date:</span> {{ date('F j, Y', strtotime($booking->event_date)) }}
                </div>
                <div class="info-row">
                    <span class="info-label">Location:</span> {{ $booking->address }}
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span> <strong style="color: #ff9800;">{{ ucfirst($booking->status) }}</strong>
                </div>
            </div>

            <div class="button-container">
                <a href="{{ url('/login') }}" class="login-button">Login to Your Account</a>
            </div>

            <h3>What You Can Do:</h3>
            <ul>
                <li>✅ View and manage your booking details</li>
                <li>✅ Update event preferences and music selections</li>
                <li>✅ Track booking status and assigned artist</li>
                <li>✅ Make payments for your booking</li>
                <li>✅ Submit reviews after your event</li>
                <li>✅ Create additional bookings for future events</li>
            </ul>

            <p>If you have any questions or need assistance, please don't hesitate to contact us or reach out to {{ $booking->company->name ?? 'our support team' }}.</p>

            <p>We look forward to making your event unforgettable! 🎵</p>

            <p>Best regards,<br>
            <strong>The StageDesk Pro Team</strong></p>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>If you did not request this account or have concerns, please contact us immediately.</p>
            <p>&copy; {{ date('Y') }} StageDesk Pro. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
