<?php

namespace App\Mail;

use App\Models\BookingRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerBookingCancelledNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $cancelledBy;
    public $reason;
    public $recipient;

    public function __construct(BookingRequest $booking, User $cancelledBy, string $reason, User $recipient)
    {
        $this->booking = $booking;
        $this->cancelledBy = $cancelledBy;
        $this->reason = $reason;
        $this->recipient = $recipient;
    }

    public function build()
    {
        return $this->subject('Customer Cancelled Booking #' . ($this->booking->tracking_code ?? $this->booking->id))
            ->view('emails.customer-booking-cancelled');
    }
}
