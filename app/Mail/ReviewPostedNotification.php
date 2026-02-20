<?php

namespace App\Mail;

use App\Models\BookingRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewPostedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public BookingRequest $booking;
    public User $customer;
    public int $rating;
    public ?string $reviewText;

    public function __construct(BookingRequest $booking, User $customer, int $rating, ?string $reviewText = null)
    {
        $this->booking = $booking->loadMissing(['company', 'assignedArtist.user', 'eventType']);
        $this->customer = $customer;
        $this->rating = $rating;
        $this->reviewText = $reviewText;
    }

    public function build()
    {
        return $this->subject('New Customer Review Posted - Booking #' . ($this->booking->tracking_code ?? $this->booking->id))
            ->view('emails.review-posted-notification');
    }
}
