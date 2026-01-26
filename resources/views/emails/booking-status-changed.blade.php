@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">Booking Status Updated</h2>

    <p>Hello {{ $booking->customer->name }},</p>

    <p>The status of your booking has been updated.</p>

    @php
        $statusColors = [
            'pending' => '#f59e0b',
            'confirmed' => '#22c55e',
            'completed' => '#0284c7',
            'rejected' => '#ef4444',
            'cancelled' => '#6b7280',
        ];
        $statusColor = $statusColors[$newStatus] ?? '#6366f1';
    @endphp

    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 20px;">
            <div style="text-align: center;">
                <p style="margin: 0; color: #666; font-size: 14px;">Previous Status</p>
                <p style="margin: 5px 0; font-size: 18px; font-weight: bold; color: #999;">
                    {{ ucfirst($oldStatus) }}
                </p>
            </div>
            <div style="font-size: 24px; color: #ccc;">→</div>
            <div style="text-align: center;">
                <p style="margin: 0; color: #666; font-size: 14px;">Current Status</p>
                <p style="margin: 5px 0; font-size: 18px; font-weight: bold; color: {{ $statusColor }};">
                    {{ ucfirst($newStatus) }}
                </p>
            </div>
        </div>
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
            <th>Event Time</th>
            <td>{{ \Carbon\Carbon::parse($booking->event_time)->format('h:i A') }}</td>
        </tr>
        @if($booking->artist)
        <tr>
            <th>Artist</th>
            <td>{{ $booking->artist->user->name ?? 'N/A' }}</td>
        </tr>
        @endif
    </table>

    @if($newStatus === 'confirmed')
        <div class="success-box">
            <strong>✓ Booking Confirmed!</strong><br>
            Your booking has been confirmed. The artist will perform at your event as scheduled.
        </div>
    @elseif($newStatus === 'completed')
        <div class="success-box">
            <strong>✓ Event Completed!</strong><br>
            Thank you for choosing {{ config('app.name') }}. We hope your event was a success!
        </div>
    @elseif($newStatus === 'rejected')
        <div class="warning-box">
            <strong>⚠️ Booking Rejected</strong><br>
            Unfortunately, your booking could not be confirmed. Please contact support for more information.
        </div>
    @elseif($newStatus === 'cancelled')
        <div class="warning-box">
            <strong>⚠️ Booking Cancelled</strong><br>
            Your booking has been cancelled. If you have any questions, please contact our support team.
        </div>
    @endif

    <p style="margin-top: 25px;">Log in to your account for more details and to manage your booking.</p>

    <p>If you have any questions, please contact our support team.</p>

    <p>Best regards,<br>The {{ config('app.name') }} Team</p>
@endsection
