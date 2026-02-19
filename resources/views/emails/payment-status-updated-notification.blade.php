@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">Payment Status Updated</h2>

    <p>Hello {{ $recipient->name ?? 'Company Admin' }},</p>

    <p>
        Your {{ $payment->type }} payment has been <strong>{{ ucfirst($status) }}</strong> by master admin.
    </p>

    <table class="details-table">
        <tr>
            <th>Payment ID</th>
            <td><strong>#{{ $payment->id }}</strong></td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ ucfirst($status) }}</td>
        </tr>
        <tr>
            <th>Type</th>
            <td>{{ ucfirst($payment->type) }}</td>
        </tr>
        <tr>
            <th>Amount</th>
            <td>{{ $payment->currency ?? 'USD' }} {{ number_format((float) $payment->amount, 2) }}</td>
        </tr>
        <tr>
            <th>Transaction ID</th>
            <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
        </tr>
        @if($payment->type === 'booking' && $payment->bookingRequest)
            <tr>
                <th>Booking</th>
                <td>#{{ $payment->bookingRequest->id }}</td>
            </tr>
        @endif
        @if($payment->type === 'subscription' && $payment->subscription)
            <tr>
                <th>Subscription</th>
                <td>#{{ $payment->subscription->id }}</td>
            </tr>
        @endif
        @if(!empty($notes))
            <tr>
                <th>Admin Notes</th>
                <td>{{ $notes }}</td>
            </tr>
        @endif
    </table>

    <p style="margin-top: 20px;">
        You can view complete details from your dashboard.
    </p>
@endsection
