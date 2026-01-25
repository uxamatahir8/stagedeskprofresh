@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">New Booking Created</h2>

    <p>Hello {{ $companyAdmin->name }},</p>

    <p>A new booking has been created for your company by the Master Admin. Please review the details below:</p>

    <div class="success-box">
        <strong>✓ New Booking Assigned to Your Company</strong><br>
        Booking ID: <strong>{{ $booking->booking_id }}</strong>
    </div>

    <h3 style="color: #333; margin: 25px 0 15px 0;">Booking Details</h3>

    <table class="details-table">
        <tr>
            <th>Booking ID</th>
            <td><strong>{{ $booking->booking_id }}</strong></td>
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
            <th>Customer Email</th>
            <td>{{ $booking->email }}</td>
        </tr>
        <tr>
            <th>Customer Phone</th>
            <td>{{ $booking->phone }}</td>
        </tr>
        <tr>
            <th>Address</th>
            <td>{{ $booking->address }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td><strong style="color: #f59e0b;">{{ ucfirst($booking->status) }}</strong></td>
        </tr>
    </table>

    @if($booking->assigned_artist_id)
    <div class="success-box">
        <strong>🎵 Artist Assigned:</strong><br>
        {{ $booking->assignedArtist->user->name ?? 'N/A' }}
    </div>
    @else
    <div class="warning-box">
        <strong>⚠️ Action Required:</strong><br>
        No artist has been assigned to this booking yet. Please log in to your dashboard to assign an available DJ/artist.
    </div>
    @endif

    @if($booking->additional_notes)
    <div style="margin: 20px 0;">
        <strong>Additional Notes:</strong>
        <p style="margin: 10px 0; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
            {{ $booking->additional_notes }}
        </p>
    </div>
    @endif

    <div class="button-container">
        <a href="{{ url('/bookings/' . $booking->id) }}" class="cta-button">View Booking Details</a>
    </div>

    <p style="margin-top: 30px;">
        This booking has been created by the Master Admin and assigned to your company. You can manage artists, update booking details, and communicate with the customer through your dashboard.
    </p>

    <p>
        If you have any questions or need assistance, please contact the Master Admin or support team.
    </p>

    <p style="margin-top: 20px;">
        Best regards,<br>
        <strong>StageDesk Pro Team</strong>
    </p>
@endsection
