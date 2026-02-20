<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_requests_id',
        'user_id',
        'subscription_id',
        'amount',
        'currency',
        'transaction_id',
        'payment_method',
        'payment_method_id',
        'submitted_to_company_id',
        'submitted_to_scope',
        'attachment',
        'type',
        'status',
        'verified_by',
        'verified_at',
        'admin_notes',
    ];

    public function bookingRequest()
    {
        return $this->belongsTo(BookingRequest::class, 'booking_requests_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(CompanySubscription::class, 'subscription_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
