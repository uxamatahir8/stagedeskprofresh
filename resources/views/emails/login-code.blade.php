@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">Your Login Code</h2>

    <p>Hello,</p>

    <p>You requested to log in to your {{ config('app.name') }} account using a verification code. Use the code below to complete your login:</p>

    <div class="code-box">
        <p style="margin: 0 0 10px 0; color: #666; font-size: 14px;">Your Login Code:</p>
        <div class="code">{{ $code }}</div>
    </div>

    <div class="info-box">
        <strong>📧 Email:</strong> {{ $email }}<br>
        <strong>⏱️ Valid for:</strong> {{ $expiresInMinutes }} minutes<br>
        <strong>🔒 One-time use only</strong>
    </div>

    <p>Simply enter this code on the login page to access your account.</p>

    <div class="warning-box">
        <strong>⚠️ Security Alert:</strong><br>
        If you didn't request this code, please ignore this email and ensure your account is secure. Someone may have entered your email address by mistake.
    </div>

    <p style="margin-top: 20px; font-size: 14px; color: #666;">
        For security reasons, this code will expire in {{ $expiresInMinutes }} minutes and can only be used once.
    </p>

    <p>Best regards,<br>The {{ config('app.name') }} Team</p>
@endsection
