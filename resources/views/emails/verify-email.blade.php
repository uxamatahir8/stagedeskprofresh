@extends('emails.layout')

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 50%; margin-bottom: 20px;">
            <span style="font-size: 40px;">✉️</span>
        </div>
        <h2 style="color: #333; margin: 0; font-size: 28px; font-weight: 700;">Verify Your Email Address</h2>
        <p style="color: #666; margin-top: 10px; font-size: 16px;">Complete your registration to get started</p>
    </div>

    <p style="font-size: 16px; line-height: 1.6; color: #333;">Hello <strong>{{ $user->name }}</strong>,</p>

    <p style="font-size: 16px; line-height: 1.6; color: #555;">
        Welcome to <strong>{{ config('app.name') }}</strong>! We're excited to have you on board. To ensure the security of your account and unlock all features, please verify your email address by clicking the button below.
    </p>

    <div style="text-align: center; margin: 35px 0;">
        <a href="{{ $verificationUrl }}" class="button" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 16px 40px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; display: inline-block; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); transition: all 0.3s ease;">
            ✓ Verify Email Address
        </a>
    </div>

    <div class="info-box" style="background: #f0f9ff; border-left: 4px solid #3b82f6; padding: 20px; border-radius: 8px; margin: 25px 0;">
        <strong style="color: #1e40af; font-size: 16px; display: block; margin-bottom: 10px;">🎯 Why verify your email?</strong>
        <ul style="margin: 10px 0; padding-left: 20px; color: #374151; line-height: 1.8;">
            <li><strong>Secure your account</strong> - Prevent unauthorized access</li>
            <li><strong>Enable email code login</strong> - Quick passwordless access</li>
            <li><strong>Get important notifications</strong> - Stay updated on bookings</li>
            <li><strong>Password recovery</strong> - Reset your password if needed</li>
            <li><strong>Full platform access</strong> - Unlock all features</li>
        </ul>
    </div>

    <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; border-radius: 8px; margin: 25px 0;">
        <p style="margin: 0; color: #92400e; font-size: 14px; line-height: 1.6;">
            <strong>⏰ Quick Action Required:</strong> This verification link will expire in <strong>24 hours</strong>. Verify now to avoid delays in accessing your account.
        </p>
    </div>

    <div style="background: #f9fafb; border: 1px solid #e5e7eb; padding: 20px; border-radius: 8px; margin: 25px 0;">
        <p style="margin: 0 0 10px 0; font-size: 14px; color: #6b7280;">
            <strong>Button not working?</strong> Copy and paste this link into your browser:
        </p>
        <p style="margin: 0; word-break: break-all; font-size: 13px;">
            <a href="{{ $verificationUrl }}" style="color: #6366f1; text-decoration: underline;">{{ $verificationUrl }}</a>
        </p>
    </div>

    <div class="warning-box" style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 15px; border-radius: 8px; margin: 25px 0;">
        <p style="margin: 0; color: #991b1b; font-size: 14px; line-height: 1.6;">
            <strong>🔒 Security Notice:</strong> If you didn't create an account with {{ config('app.name') }}, please ignore this email. Your email may have been entered by mistake.
        </p>
    </div>

    <p style="font-size: 15px; color: #6b7280; line-height: 1.6; margin-top: 30px;">
        Need help? Our support team is here to assist you. Simply reply to this email or contact us through the platform.
    </p>

    <p style="font-size: 15px; color: #333; margin-top: 25px;">
        Best regards,<br>
        <strong>The {{ config('app.name') }} Team</strong>
    </p>
@endsection
