@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">New Payment Submitted</h2>

    <p>Hello {{ $recipient->name ?? 'Team' }},</p>

    <p>
        A new {{ $payment->type }} payment has been submitted and is pending verification.
    </p>

    <table class="details-table">
        <tr>
            <th>Payment ID</th>
            <td><strong>#{{ $payment->id }}</strong></td>
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
            <th>Method</th>
            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'N/A')) }}</td>
        </tr>
        <tr>
            <th>Transaction ID</th>
            <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Submitted By</th>
            <td>{{ $submittedBy->name }} ({{ $submittedBy->email }})</td>
        </tr>
        @if($payment->type === 'booking' && $payment->bookingRequest)
            <tr>
                <th>Booking</th>
                <td>#{{ $payment->bookingRequest->id }}</td>
            </tr>
            <tr>
                <th>Company</th>
                <td>{{ $payment->bookingRequest->company->name ?? 'N/A' }}</td>
            </tr>
        @endif
        @if($payment->type === 'subscription' && $payment->subscription)
            <tr>
                <th>Subscription</th>
                <td>#{{ $payment->subscription->id }}</td>
            </tr>
            <tr>
                <th>Company</th>
                <td>{{ $payment->subscription->company->name ?? 'N/A' }}</td>
            </tr>
        @endif
    </table>

    <p style="margin-top: 20px;">
        Please review this payment in the dashboard.
    </p>
@endsection
