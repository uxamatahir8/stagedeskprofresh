<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusUpdatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Payment $payment;
    public string $status;
    public ?string $notes;
    public User $recipient;

    public function __construct(Payment $payment, string $status, ?string $notes, User $recipient)
    {
        $this->payment = $payment->loadMissing(['bookingRequest.company', 'subscription.company', 'user.company']);
        $this->status = $status;
        $this->notes = $notes;
        $this->recipient = $recipient;
    }

    public function build()
    {
        $statusLabel = ucfirst($this->status);
        $typeLabel = ucfirst($this->payment->type);

        return $this->subject('Payment ' . $statusLabel . ': ' . $typeLabel . ' Payment #' . $this->payment->id)
            ->view('emails.payment-status-updated-notification');
    }
}
