@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">Artist Assigned to Booking</h2>

    <p>Hello {{ $companyAdmin->name }},</p>

    <p>The Master Admin has assigned an artist from your company to a booking. Please review the details below:</p>

    <div class="success-box">
        <strong>✓ Artist Assignment Complete</strong><br>
        Booking ID: <strong>{{ $booking->booking_id }}</strong>
    </div>

    <h3 style="color: #333; margin: 25px 0 15px 0;">Assigned Artist</h3>

    <table class="details-table">
        <tr>
            <th>Artist Name</th>
            <td><strong>{{ $artist->user->name }}</strong></td>
        </tr>
        <tr>
            <th>Artist Email</th>
            <td>{{ $artist->user->email }}</td>
        </tr>
        @if($artist->user->phone)
        <tr>
            <th>Artist Phone</th>
            <td>{{ $artist->user->phone }}</td>
        </tr>
        @endif
    </table>

    <h3 style="color: #333; margin: 25px 0 15px 0;">Booking Details</h3>

    <table class="details-table">
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

    <div class="info-box" style="background-color: #e3f2fd; border-left: 4px solid #2196F3; padding: 15px; margin: 20px 0; border-radius: 5px;">
        <strong>📧 Artist Notified:</strong><br>
        The assigned artist has been notified via email and can now accept or reject this booking through their dashboard.
    </div>

    @if($booking->company_notes)
    <div style="margin: 20px 0;">
        <strong>Company Notes:</strong>
        <p style="margin: 10px 0; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
            {{ $booking->company_notes }}
        </p>
    </div>
    @endif

    <div class="button-container">
        <a href="{{ url('/bookings/' . $booking->id) }}" class="cta-button">View Booking Details</a>
    </div>

    <p style="margin-top: 30px;">
        You can monitor the booking status and artist response through your dashboard. The booking status is currently <strong>{{ ucfirst($booking->status) }}</strong> and will be updated when the artist accepts or rejects the assignment.
    </p>

    <p>
        If you need to reassign this booking to a different artist or have any questions, please log in to your dashboard or contact the Master Admin.
    </p>

    <p style="margin-top: 20px;">
        Best regards,<br>
        <strong>StageDesk Pro Team</strong>
    </p>
@endsection
