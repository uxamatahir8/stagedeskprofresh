@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">New Booking Assignment</h2>

    <p>Hello {{ $booking->artist->user->name }},</p>

    <p>You have been assigned to a new booking! Please review the details below and confirm your availability.</p>

    <div class="info-box">
        <strong>🎭 New Event Assignment</strong><br>
        Booking ID: <strong>{{ $booking->booking_id }}</strong>
    </div>

    <h3 style="color: #333; margin: 25px 0 15px 0;">Event Details</h3>

    <table class="details-table">
        <tr>
            <th>Event Type</th>
            <td><strong>{{ $booking->eventType->event_type ?? 'N/A' }}</strong></td>
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
            <th>Number of Guests</th>
            <td>{{ $booking->number_of_guests ?? 'Not specified' }}</td>
        </tr>
        @if($booking->total_price)
        <tr>
            <th>Payment</th>
            <td><strong>${{ number_format($booking->total_price, 2) }}</strong></td>
        </tr>
        @endif
    </table>

    <h3 style="color: #333; margin: 25px 0 15px 0;">Customer Information</h3>

    <table class="details-table">
        <tr>
            <th>Customer Name</th>
            <td>{{ $booking->customer->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $booking->customer->email }}</td>
        </tr>
        @if($booking->customer->phone)
        <tr>
            <th>Phone</th>
            <td>{{ $booking->customer->phone }}</td>
        </tr>
        @endif
    </table>

    @if($booking->special_requests)
    <div style="margin: 20px 0;">
        <strong>Special Requests:</strong>
        <p style="margin: 10px 0; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
            {{ $booking->special_requests }}
        </p>
    </div>
    @endif

    <div class="warning-box">
        <strong>⚠️ Action Required:</strong><br>
        Please log in to your dashboard to accept or reject this booking. The customer is waiting for your confirmation.
    </div>

    <div style="text-align: center; margin: 25px 0;">
        <a href="{{ config('app.url') }}/artist/dashboard" class="button">View Booking Details</a>
    </div>

    <p>If you have any questions about this booking, please contact the company admin or our support team.</p>

    <p>Best regards,<br>The {{ config('app.name') }} Team</p>
@endsection
