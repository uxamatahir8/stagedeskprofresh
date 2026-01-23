@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">Welcome to {{ config('app.name') }}!</h2>

    <p>Hello {{ $user->name }},</p>

    <p>Thank you for registering with {{ config('app.name') }}. To complete your registration and activate your account, please verify your email address.</p>

    <div class="info-box">
        <strong>Why verify your email?</strong>
        <ul style="margin: 10px 0;">
            <li>Secure your account</li>
            <li>Receive important notifications</li>
            <li>Enable password recovery</li>
            <li>Access all platform features</li>
        </ul>
    </div>

    <div style="text-align: center;">
        <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>
    </div>

    <p style="margin-top: 20px; font-size: 14px; color: #666;">
        If the button doesn't work, copy and paste this link into your browser:<br>
        <a href="{{ $verificationUrl }}" style="color: #6366f1; word-break: break-all;">{{ $verificationUrl }}</a>
    </p>

    <div class="warning-box">
        <strong>⚠️ Security Note:</strong><br>
        This verification link will expire in 24 hours. If you didn't create an account, please ignore this email.
    </div>

    <p>If you have any questions, feel free to contact our support team.</p>

    <p>Best regards,<br>The {{ config('app.name') }} Team</p>
@endsection
