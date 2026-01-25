@extends('emails.layout')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 50%; margin-bottom: 20px;">
            <span style="font-size: 40px;">🔐</span>
        </div>
        <h2 style="color: #333; margin: 0; font-size: 28px; font-weight: 700;">Your Account Has Been Created</h2>
        <p style="color: #666; margin-top: 10px; font-size: 16px;">Welcome to {{ config('app.name') }}</p>
    </div>

    <p style="font-size: 16px; line-height: 1.6; color: #333;">Hello <strong>{{ $user->name }}</strong>,</p>

    <p style="font-size: 16px; line-height: 1.6; color: #555;">
        Your account has been created on <strong>{{ config('app.name') }}</strong>. Below are your login credentials. For security reasons, you'll be required to change your password when you first log in.
    </p>

    <div style="background: #f0f9ff; border: 2px solid #3b82f6; padding: 25px; border-radius: 12px; margin: 25px 0;">
        <h3 style="color: #1e40af; margin: 0 0 15px 0; font-size: 18px;">🔑 Your Login Credentials</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 10px 0; color: #6b7280; font-weight: 600; width: 30%;">Email:</td>
                <td style="padding: 10px 0; color: #111827; font-weight: 700;">{{ $user->email }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; color: #6b7280; font-weight: 600;">Temporary Password:</td>
                <td style="padding: 10px 0;">
                    <code style="background: #1f2937; color: #fbbf24; padding: 8px 15px; border-radius: 6px; font-size: 15px; font-weight: 600; letter-spacing: 1px;">{{ $temporaryPassword }}</code>
                </td>
            </tr>
        </table>
    </div>

    <div class="warning-box" style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 20px; border-radius: 8px; margin: 25px 0;">
        <p style="margin: 0; color: #92400e; font-size: 15px; line-height: 1.6;">
            <strong>⚠️ Important:</strong> This is a <strong>temporary password</strong>. You must change it on your first login for security purposes. You will not be able to access the platform until you set a new password.
        </p>
    </div>

    <div style="text-align: center; margin: 35px 0;">
        <a href="{{ $loginUrl }}" class="button" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 16px 40px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; display: inline-block; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); transition: all 0.3s ease;">
            🚀 Login to Your Account
        </a>
    </div>

    <div class="info-box" style="background: #f0fdf4; border-left: 4px solid #10b981; padding: 20px; border-radius: 8px; margin: 25px 0;">
        <strong style="color: #065f46; font-size: 16px; display: block; margin-bottom: 10px;">📋 Next Steps:</strong>
        <ol style="margin: 10px 0; padding-left: 20px; color: #374151; line-height: 1.8;">
            <li>Click the "Login to Your Account" button above</li>
            <li>Enter your email and temporary password</li>
            <li>You'll be prompted to create a new password</li>
            <li>Set a strong password (min 10 characters with uppercase, lowercase, number, and special character)</li>
            <li>Start using {{ config('app.name') }}!</li>
        </ol>
    </div>

    <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 20px; border-radius: 8px; margin: 25px 0;">
        <strong style="color: #991b1b; font-size: 15px; display: block; margin-bottom: 10px;">🔒 Security Tips:</strong>
        <ul style="margin: 10px 0; padding-left: 20px; color: #7f1d1d; line-height: 1.8;">
            <li>Never share your password with anyone</li>
            <li>Use a unique password for this account</li>
            <li>Don't use easily guessable information</li>
            <li>Enable two-factor authentication if available</li>
            <li>Log out when using shared computers</li>
        </ul>
    </div>

    <div style="background: #f9fafb; border: 1px solid #e5e7eb; padding: 20px; border-radius: 8px; margin: 25px 0;">
        <p style="margin: 0 0 10px 0; font-size: 14px; color: #6b7280;">
            <strong>Having trouble logging in?</strong> Copy this link and paste it into your browser:
        </p>
        <p style="margin: 0; word-break: break-all; font-size: 13px;">
            <a href="{{ $loginUrl }}" style="color: #6366f1; text-decoration: underline;">{{ $loginUrl }}</a>
        </p>
    </div>

    <div style="background: #fffbeb; border: 1px solid #fbbf24; padding: 15px; border-radius: 8px; margin: 25px 0;">
        <p style="margin: 0; color: #92400e; font-size: 14px; line-height: 1.6;">
            <strong>💡 Pro Tip:</strong> After changing your password, you can also enable Email Code Login for quick access without remembering passwords.
        </p>
    </div>

    <p style="font-size: 15px; color: #6b7280; line-height: 1.6; margin-top: 30px;">
        Need assistance? Our support team is ready to help. Reply to this email or contact us through the platform.
    </p>

    <p style="font-size: 15px; color: #333; margin-top: 25px;">
        Best regards,<br>
        <strong>The {{ config('app.name') }} Team</strong>
    </p>

    <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; margin-top: 30px;">
        <p style="margin: 0; font-size: 13px; color: #6b7280; text-align: center; line-height: 1.6;">
            This email was sent because an account was created for you on {{ config('app.name') }}. <br>
            If you believe this was sent in error, please contact our support team immediately.
        </p>
    </div>
@endsection
