@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">New Booking Assignment</h2>

    <p>Hello {{ $booking->assignedArtist->user->name ?? 'Artist' }},</p>

    <p>You have been assigned to a new booking! Please review the details below and confirm your availability.</p>

    <div class="info-box">
        <strong>🎭 New Event Assignment</strong><br>
        Booking ID: <strong>#{{ $booking->id }}</strong>
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
            <td>
                @if($booking->start_time || $booking->end_time)
                    {{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : '' }}
                    @if($booking->end_time)
                        - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                    @endif
                @else
                    {{ $booking->wedding_time ? \Carbon\Carbon::parse($booking->wedding_time)->format('h:i A') : 'Not specified' }}
                @endif
            </td>
        </tr>
        <tr>
            <th>Venue</th>
            <td>{{ $booking->wedding_location ?? $booking->address ?? 'N/A' }}</td>
        </tr>
    </table>

    <h3 style="color: #333; margin: 25px 0 15px 0;">Customer Information</h3>

    <table class="details-table">
        <tr>
            <th>Customer Name</th>
            <td>{{ $booking->user->name ?? ($booking->name . ' ' . $booking->surname) }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $booking->user->email ?? $booking->email }}</td>
        </tr>
        @if(($booking->user->profile->phone ?? null) || $booking->phone)
        <tr>
            <th>Phone</th>
            <td>{{ $booking->user->profile->phone ?? $booking->phone }}</td>
        </tr>
        @endif
    </table>

    @if($booking->additional_notes)
    <div style="margin: 20px 0;">
        <strong>Additional Notes:</strong>
        <p style="margin: 10px 0; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
            {{ $booking->additional_notes }}
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
