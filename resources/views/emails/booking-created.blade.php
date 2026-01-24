@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">Booking Confirmation</h2>

    <p>Hello {{ $booking->customer->name }},</p>

    <p>Thank you for your booking! We have received your event booking request and it's being processed.</p>

    <div class="success-box">
        <strong>✓ Booking Successfully Created</strong><br>
        Your booking ID: <strong>{{ $booking->booking_id }}</strong>
    </div>

    <h3 style="color: #333; margin: 25px 0 15px 0;">Booking Details</h3>

    <table class="details-table">
        <tr>
            <th>Booking ID</th>
            <td><strong>{{ $booking->booking_id }}</strong></td>
        </tr>
        <tr>
            <th>Event Type</th>
            <td>{{ $booking->eventType->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Event Date</th>
            <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('F d, Y') }}</td>
        </tr>
        <tr>
            <th>Event Time</th>
            <td>{{ \Carbon\Carbon::parse($booking->event_time)->format('h:i A') }}</td>
        </tr>
        <tr>
            <th>Duration</th>
            <td>{{ $booking->duration }} hours</td>
        </tr>
        <tr>
            <th>Venue</th>
            <td>{{ $booking->venue }}</td>
        </tr>
        <tr>
            <th>City</th>
            <td>{{ $booking->city }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td><strong style="color: #f59e0b;">{{ ucfirst($booking->status) }}</strong></td>
        </tr>
        @if($booking->total_price)
        <tr>
            <th>Total Price</th>
            <td><strong>${{ number_format($booking->total_price, 2) }}</strong></td>
        </tr>
        @endif
    </table>

    <div class="info-box">
        <strong>📋 What's Next?</strong>
        <ul style="margin: 10px 0;">
            <li>Your booking is currently being reviewed</li>
            <li>We will assign the best artist for your event</li>
            <li>You'll receive an email once an artist is assigned</li>
            <li>Track your booking status in your dashboard</li>
        </ul>
    </div>

    @if($booking->special_requests)
    <div style="margin: 20px 0;">
        <strong>Special Requests:</strong>
        <p style="margin: 10px 0; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
            {{ $booking->special_requests }}
        </p>
    </div>
    @endif

    <p style="margin-top: 25px;">You can view and manage your booking by logging into your account.</p>

    <p>If you have any questions, please don't hesitate to contact us.</p>

    <p>Best regards,<br>The {{ config('app.name') }} Team</p>
@endsection
