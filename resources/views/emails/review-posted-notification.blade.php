@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">New Review Posted</h2>

    <p>Hello {{ $booking->company->name ?? 'Company Team' }},</p>

    <p>A customer has posted a new review for a booking.</p>

    <table class="details-table">
        <tr>
            <th>Booking ID</th>
            <td><strong>#{{ $booking->id }}</strong></td>
        </tr>
        <tr>
            <th>Customer</th>
            <td>{{ $customer->name }} ({{ $customer->email }})</td>
        </tr>
        <tr>
            <th>Artist</th>
            <td>{{ $booking->assignedArtist->user->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Rating</th>
            <td>{{ $rating }}/5</td>
        </tr>
        @if(!empty($reviewText))
        <tr>
            <th>Review</th>
            <td>{{ $reviewText }}</td>
        </tr>
        @endif
    </table>

    <p style="margin-top: 20px;">Please review this feedback in your dashboard.</p>
@endsection
