@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">Payment Required for Your Booking</h2>

    <p>Hello {{ $booking->user ? $booking->user->name : $booking->name . ' ' . $booking->surname }},</p>

    <p>Your booking has been created successfully. To confirm your booking, please complete the payment using one of the payment methods below.</p>

    <div class="success-box">
        <strong>Booking #{{ $booking->tracking_code ?? $booking->id }}</strong><br>
        {{ $booking->eventType->event_type ?? 'Event' }} — {{ \Carbon\Carbon::parse($booking->event_date)->format('F d, Y') }}
    </div>

    <p style="margin-top: 20px;">You can submit your payment by logging into your account and going to <strong>Payments → Add Payment</strong>, then select your booking and the amount.</p>

    @if($paymentMethods->isNotEmpty())
    <h3 style="color: #333; margin: 25px 0 15px 0;">Payment methods ({{ $booking->company->name ?? 'Company' }})</h3>
    <p>Use one of these methods to pay. After transferring, log in and record your payment with the transaction reference.</p>
    <table class="details-table" style="width: 100%; border-collapse: collapse; margin: 15px 0;">
        <thead>
            <tr style="background: #f8f9fa;">
                <th style="padding: 10px; text-align: left;">Method</th>
                <th style="padding: 10px; text-align: left;">Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentMethods as $method)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;"><strong>{{ $method->display_name }}</strong></td>
                <td style="padding: 10px;">
                    @if($method->account_name) Account: {{ $method->account_name }}<br> @endif
                    @if($method->account_number) Account #: {{ $method->account_number }}<br> @endif
                    @if($method->iban) IBAN: {{ $method->iban }}<br> @endif
                    @if($method->swift_code) SWIFT: {{ $method->swift_code }}<br> @endif
                    @if($method->wallet_email) Email: {{ $method->wallet_email }}<br> @endif
                    @if($method->instructions)<p style="margin: 5px 0;">{{ $method->instructions }}</p> @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <p style="margin-top: 25px;">After you have made the payment, please log in and submit the payment details so we can confirm your booking.</p>

    <p>Best regards,<br>The {{ config('app.name') }} Team</p>
@endsection
