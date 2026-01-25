<?php

namespace App\Mail;

use App\Models\BookingRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewBookingForCompany extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $companyAdmin;

    /**
     * Create a new message instance.
     *
     * @param BookingRequest $booking
     * @param User $companyAdmin
     */
    public function __construct(BookingRequest $booking, User $companyAdmin)
    {
        $this->booking = $booking;
        $this->companyAdmin = $companyAdmin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Booking Created - StageDesk Pro')
            ->view('emails.new-booking-for-company');
    }
}
