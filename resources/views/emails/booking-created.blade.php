@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">Booking Confirmation</h2>

    <p>Hello {{ $booking->user ? $booking->user->name : $booking->name . ' ' . $booking->surname }},</p>

    <p>Thank you for your booking! We have received your event booking request and it's being processed.</p>

    <div class="success-box">
        <strong>✓ Booking Successfully Created</strong><br>
        Your booking ID: <strong>#{{ $booking->id }}</strong>
    </div>

    <h3 style="color: #333; margin: 25px 0 15px 0;">Booking Details</h3>

    <table class="details-table">
        <tr>
            <th>Booking ID</th>
            <td><strong>#{{ $booking->id }}</strong></td>
        </tr>
        <tr>
            <th>Event Type</th>
            <td>{{ $booking->eventType->event_type ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Event Date</th>
            <td>{{ \Carbon\Carbon::parse($booking->event_date)->format('F d, Y') }}</td>
        </tr>
        <tr>
            <th>Customer Name</th>
            <td>{{ $booking->name }} {{ $booking->surname }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $booking->email }}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>{{ $booking->phone }}</td>
        </tr>
        <tr>
            <th>Address</th>
            <td>{{ $booking->address }}</td>
        </tr>
        @if($booking->wedding_date)
        <tr>
            <th>Wedding Date</th>
            <td>{{ \Carbon\Carbon::parse($booking->wedding_date)->format('F d, Y') }}</td>
        </tr>
        @endif
        @if($booking->wedding_time)
        <tr>
            <th>Wedding Time</th>
            <td>{{ $booking->wedding_time }}</td>
        </tr>
        @endif
        <tr>
            <th>Status</th>
            <td><strong style="color: #f59e0b;">{{ ucfirst($booking->status) }}</strong></td>
        </tr>
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

    @if($booking->additional_notes)
    <div style="margin: 20px 0;">
        <strong>Additional Notes:</strong>
        <p style="margin: 10px 0; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
            {{ $booking->additional_notes }}
        </p>
    </div>
    @endif

    <p style="margin-top: 25px;">You can view and manage your booking by logging into your account.</p>

    <p>If you have any questions, please don't hesitate to contact us.</p>

    <p>Best regards,<br>The {{ config('app.name') }} Team</p>
@endsection
