<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $booking;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $password
     * @param \App\Models\BookingRequest $booking
     */
    public function __construct($user, $password, $booking)
    {
        $this->user = $user;
        $this->password = $password;
        $this->booking = $booking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Account Has Been Created - StageDesk Pro')
            ->view('emails.customer-account-created');
    }
}
