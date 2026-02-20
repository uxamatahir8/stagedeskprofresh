<?php

namespace App\Mail;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class CustomerMakePaymentForBooking extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BookingRequest $booking,
        public Collection $paymentMethods
    ) {
        $this->booking->loadMissing(['company', 'eventType']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Required for Your Booking #' . ($this->booking->tracking_code ?? $this->booking->id),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-make-payment-booking',
        );
    }
}
