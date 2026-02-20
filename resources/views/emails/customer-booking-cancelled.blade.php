@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">Booking Cancelled by Customer</h2>

    <p>Hello {{ $recipient->name }},</p>
    <p>A customer has cancelled a booking.</p>

    <table class="details-table">
        <tr>
            <th>Booking ID</th>
            <td><strong>#{{ $booking->tracking_code ?? $booking->id }}</strong></td>
        </tr>
        <tr>
            <th>Customer</th>
            <td>{{ $booking->name }} {{ $booking->surname }} ({{ $booking->email }})</td>
        </tr>
        <tr>
            <th>Cancelled By</th>
            <td>{{ $cancelledBy->name }} ({{ $cancelledBy->email }})</td>
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
            <th>Company</th>
            <td>{{ $booking->company->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Reason</th>
            <td>{{ $reason }}</td>
        </tr>
    </table>

    <p style="margin-top: 20px;">Please review this cancellation in the dashboard.</p>
@endsection
