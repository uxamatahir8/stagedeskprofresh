<?php

namespace App\Mail;

use App\Models\BookingRequest;
use App\Models\Artist;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ArtistAssignedToCompany extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $artist;
    public $companyAdmin;

    /**
     * Create a new message instance.
     *
     * @param BookingRequest $booking
     * @param Artist $artist
     * @param User $companyAdmin
     */
    public function __construct(BookingRequest $booking, Artist $artist, User $companyAdmin)
    {
        $this->booking = $booking;
        $this->artist = $artist;
        $this->companyAdmin = $companyAdmin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Artist Assigned to Booking - StageDesk Pro')
            ->view('emails.artist-assigned-to-company');
    }
}
