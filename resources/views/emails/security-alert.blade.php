@extends('emails.layout')

@section('content')
    <h2 style="color: #ef4444; margin-bottom: 20px;">🔒 Security Alert</h2>

    <p>Hello {{ $user->name }},</p>

    @if($alertType === 'account_locked')
        <div class="warning-box">
            <strong>⚠️ Account Temporarily Locked</strong><br>
            Your account has been temporarily locked due to multiple failed login attempts.
        </div>

        <p>For your security, we have temporarily locked your account after detecting {{ $details['failed_attempts'] ?? 5 }} failed login attempts.</p>

        <table class="details-table">
            <tr>
                <th>Lock Duration</th>
                <td>{{ $details['lock_duration'] ?? 30 }} minutes</td>
            </tr>
            <tr>
                <th>IP Address</th>
                <td>{{ $details['ip_address'] ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <th>Time</th>
                <td>{{ $details['time'] ?? now()->format('F d, Y h:i A') }}</td>
            </tr>
        </table>

        <div class="info-box">
            <strong>What to do next:</strong>
            <ul style="margin: 10px 0;">
                <li>Wait for {{ $details['lock_duration'] ?? 30 }} minutes before trying again</li>
                <li>Ensure you're using the correct password</li>
                <li>If you forgot your password, use the "Forgot Password" option</li>
                <li>Contact support if you didn't make these login attempts</li>
            </ul>
        </div>

    @elseif($alertType === 'suspicious_activity')
        <div class="warning-box">
            <strong>⚠️ Suspicious Activity Detected</strong><br>
            We detected unusual activity on your account.
        </div>

        <p>We noticed some suspicious activity associated with your account and wanted to alert you immediately.</p>

        <table class="details-table">
            <tr>
                <th>Activity Type</th>
                <td>{{ $details['activity_type'] ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <th>IP Address</th>
                <td>{{ $details['ip_address'] ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ $details['location'] ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <th>Time</th>
                <td>{{ $details['time'] ?? now()->format('F d, Y h:i A') }}</td>
            </tr>
        </table>

        <div class="info-box">
            <strong>What to do next:</strong>
            <ul style="margin: 10px 0;">
                <li>If this was you, you can safely ignore this message</li>
                <li>If this wasn't you, change your password immediately</li>
                <li>Review your recent account activity</li>
                <li>Enable two-factor authentication for extra security</li>
            </ul>
        </div>

    @elseif($alertType === 'password_changed')
        <div class="success-box">
            <strong>✓ Password Changed Successfully</strong><br>
            Your account password has been updated.
        </div>

        <p>This is a confirmation that your password was successfully changed.</p>

        <table class="details-table">
            <tr>
                <th>IP Address</th>
                <td>{{ $details['ip_address'] ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <th>Time</th>
                <td>{{ $details['time'] ?? now()->format('F d, Y h:i A') }}</td>
            </tr>
        </table>

        <div class="warning-box">
            <strong>⚠️ If you didn't make this change:</strong><br>
            Contact our support team immediately. Your account may have been compromised.
        </div>

    @elseif($alertType === 'new_device_login')
        <div class="info-box">
            <strong>🔔 New Device Login</strong><br>
            Your account was accessed from a new device.
        </div>

        <p>We detected a login to your account from a device we haven't seen before.</p>

        <table class="details-table">
            <tr>
                <th>Device</th>
                <td>{{ $details['device'] ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <th>Browser</th>
                <td>{{ $details['browser'] ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <th>IP Address</th>
                <td>{{ $details['ip_address'] ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ $details['location'] ?? 'Unknown' }}</td>
            </tr>
            <tr>
                <th>Time</th>
                <td>{{ $details['time'] ?? now()->format('F d, Y h:i A') }}</td>
            </tr>
        </table>

        <div class="info-box">
            <strong>What to do next:</strong>
            <ul style="margin: 10px 0;">
                <li>If this was you, you can safely ignore this message</li>
                <li>If this wasn't you, secure your account immediately</li>
                <li>Change your password if you suspect unauthorized access</li>
            </ul>
        </div>
    @endif

    <div style="text-align: center; margin: 25px 0;">
        <a href="{{ config('app.url') }}/dashboard" class="button">Go to Dashboard</a>
    </div>

    <p style="margin-top: 25px; font-size: 14px; color: #666;">
        If you have any concerns about your account security, please contact our support team immediately.
    </p>

    <p>Best regards,<br>The {{ config('app.name') }} Security Team</p>
@endsection
