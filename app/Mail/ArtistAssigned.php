<?php

namespace App\Mail;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ArtistAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $isReassignment;

    /**
     * Create a new message instance.
     */
    public function __construct(BookingRequest $booking, bool $isReassignment = false)
    {
        $this->booking = $booking;
        $this->isReassignment = $isReassignment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $bookingRef = $this->booking->tracking_code ?? $this->booking->id;
        $subject = $this->isReassignment
            ? 'Artist Reassigned - ' . $bookingRef
            : 'Artist Assigned - ' . $bookingRef;

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.artist-assigned',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
