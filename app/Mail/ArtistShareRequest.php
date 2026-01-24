<?php

namespace App\Mail;

use App\Models\SharedArtist;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ArtistShareRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $sharedArtist;

    /**
     * Create a new message instance.
     */
    public function __construct(SharedArtist $sharedArtist)
    {
        $this->sharedArtist = $sharedArtist;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Artist Share Request - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.artist-share-request',
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
