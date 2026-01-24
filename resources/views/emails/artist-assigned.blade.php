@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">
        @if($isReassignment)
            Artist Reassigned to Your Booking
        @else
            Artist Assigned to Your Booking
        @endif
    </h2>

    <p>Hello {{ $booking->customer->name }},</p>

    @if($isReassignment)
        <p>We have reassigned a new artist to your booking. Here are the details:</p>
    @else
        <p>Great news! We have assigned an artist to your booking.</p>
    @endif

    <div class="success-box">
        <strong>✓ Artist {{ $isReassignment ? 'Reassigned' : 'Assigned' }}</strong><br>
        Booking ID: <strong>{{ $booking->booking_id }}</strong>
    </div>

    <h3 style="color: #333; margin: 25px 0 15px 0;">Artist Information</h3>

    <table class="details-table">
        <tr>
            <th>Artist Name</th>
            <td><strong>{{ $booking->artist->user->name ?? 'N/A' }}</strong></td>
        </tr>
        <tr>
            <th>Specialization</th>
            <td>{{ $booking->artist->specialization ?? 'N/A' }}</td>
        </tr>
        @if($booking->artist->bio)
        <tr>
            <th>Bio</th>
            <td>{{ $booking->artist->bio }}</td>
        </tr>
        @endif
        <tr>
            <th>Experience</th>
            <td>{{ $booking->artist->experience_years ?? 'N/A' }} years</td>
        </tr>
    </table>

    <h3 style="color: #333; margin: 25px 0 15px 0;">Event Details</h3>

    <table class="details-table">
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
            <th>Status</th>
            <td><strong style="color: #f59e0b;">{{ ucfirst($booking->status) }}</strong></td>
        </tr>
    </table>

    <div class="info-box">
        <strong>📋 What's Next?</strong>
        <ul style="margin: 10px 0;">
            <li>The artist will review your booking details</li>
            <li>You'll be notified once the artist confirms</li>
            <li>You can view artist details in your dashboard</li>
            <li>Feel free to add any special instructions</li>
        </ul>
    </div>

    <p style="margin-top: 25px;">Log in to your account to view full artist profile and booking details.</p>

    <p>If you have any questions, please contact our support team.</p>

    <p>Best regards,<br>The {{ config('app.name') }} Team</p>
@endsection
