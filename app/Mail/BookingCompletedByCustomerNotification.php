<?php

namespace App\Mail;

use App\Models\BookingRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingCompletedByCustomerNotification extends Mailable
{
    use Queueable, SerializesModels;

    public BookingRequest $booking;
    public User $recipient;

    public function __construct(BookingRequest $booking, User $recipient)
    {
        $this->booking = $booking->loadMissing(['company', 'assignedArtist.user', 'eventType', 'user']);
        $this->recipient = $recipient;
    }

    public function build()
    {
        return $this->subject('Customer Marked Booking Completed - #' . ($this->booking->tracking_code ?? $this->booking->id))
            ->view('emails.booking-completed-by-customer');
    }
}
