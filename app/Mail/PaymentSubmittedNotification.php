<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSubmittedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Payment $payment;
    public User $submittedBy;
    public User $recipient;
    public string $recipientType;

    public function __construct(Payment $payment, User $submittedBy, User $recipient, string $recipientType)
    {
        $this->payment = $payment->loadMissing(['bookingRequest.company', 'subscription.company', 'user.company']);
        $this->submittedBy = $submittedBy;
        $this->recipient = $recipient;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        $typeLabel = ucfirst($this->payment->type);
        $subjectPrefix = $this->recipientType === 'master_admin' ? 'Admin Alert' : 'Company Alert';

        return $this->subject($subjectPrefix . ': New ' . $typeLabel . ' Payment Submitted - #' . $this->payment->id)
            ->view('emails.payment-submitted-notification');
    }
}
