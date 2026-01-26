<?php

namespace App\Mail;

use App\Models\BookingRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingAcceptedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $recipient;

    /**
     * Create a new message instance.
     *
     * @param BookingRequest $booking
     * @param User $recipient
     */
    public function __construct(BookingRequest $booking, User $recipient)
    {
        $this->booking = $booking;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Booking Accepted by Artist - #' . $this->booking->id . ' - StageDesk Pro')
            ->view('emails.booking-accepted-notification');
    }
}
