@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">Booking Completed by Customer</h2>

    <p>Hello {{ $recipient->name }},</p>

    <p>A customer has marked a booking as completed.</p>

    <table class="details-table">
        <tr>
            <th>Booking ID</th>
            <td><strong>#{{ $booking->tracking_code ?? $booking->id }}</strong></td>
        </tr>
        <tr>
            <th>Company</th>
            <td>{{ $booking->company->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Artist</th>
            <td>{{ $booking->assignedArtist->user->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Customer</th>
            <td>{{ $booking->user->name ?? ($booking->name . ' ' . $booking->surname) }}</td>
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
            <th>Status</th>
            <td><strong>Completed</strong></td>
        </tr>
    </table>

    <p style="margin-top: 20px;">Please review this booking in the admin dashboard.</p>
@endsection
