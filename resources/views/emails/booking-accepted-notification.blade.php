<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Accepted</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #28a745; border-bottom: 2px solid #28a745; padding-bottom: 10px;">
            ✅ Booking Accepted by Artist
        </h2>

        <p>Hello {{ $recipient->name }},</p>

        <p>An artist has accepted a booking request. Here are the details:</p>

        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr style="background-color: #f8f9fa;">
                <td style="padding: 10px; border: 1px solid #dee2e6; font-weight: bold;">Booking ID</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">#{{ $booking->id }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #dee2e6; font-weight: bold;">Event Type</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">{{ $booking->eventType->event_type ?? 'N/A' }}</td>
            </tr>
            <tr style="background-color: #f8f9fa;">
                <td style="padding: 10px; border: 1px solid #dee2e6; font-weight: bold;">Customer</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">{{ $booking->user->name ?? 'N/A' }} ({{ $booking->user->email ?? 'N/A' }})</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #dee2e6; font-weight: bold;">Artist</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">{{ $booking->assignedArtist->user->name ?? 'N/A' }}</td>
            </tr>
            <tr style="background-color: #f8f9fa;">
                <td style="padding: 10px; border: 1px solid #dee2e6; font-weight: bold;">Event Date</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">{{ \Carbon\Carbon::parse($booking->event_date)->format('F d, Y h:i A') }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #dee2e6; font-weight: bold;">Company</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">{{ $booking->company->name ?? 'N/A' }}</td>
            </tr>
            <tr style="background-color: #f8f9fa;">
                <td style="padding: 10px; border: 1px solid #dee2e6; font-weight: bold;">Total Amount</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">${{ number_format($booking->total_amount ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #dee2e6; font-weight: bold;">Status</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;"><strong style="color: #28a745;">CONFIRMED</strong></td>
            </tr>
            <tr style="background-color: #f8f9fa;">
                <td style="padding: 10px; border: 1px solid #dee2e6; font-weight: bold;">Confirmed At</td>
                <td style="padding: 10px; border: 1px solid #dee2e6;">{{ $booking->confirmed_at ? \Carbon\Carbon::parse($booking->confirmed_at)->format('F d, Y h:i A') : 'Just now' }}</td>
            </tr>
        </table>

        @if($booking->special_requirements)
        <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #856404;">Special Requirements:</h4>
            <p style="margin-bottom: 0;">{{ $booking->special_requirements }}</p>
        </div>
        @endif

        <div style="margin: 30px 0;">
            <a href="{{ route('bookings.show', $booking->id) }}"
               style="background-color: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                View Booking Details
            </a>
        </div>

        <hr style="border: none; border-top: 1px solid #dee2e6; margin: 30px 0;">

        <p style="font-size: 12px; color: #6c757d;">
            This is an automated notification from StageDesk Pro. Please do not reply to this email.
            <br>
            If you have any questions, please contact support.
        </p>
    </div>
</body>
</html>
