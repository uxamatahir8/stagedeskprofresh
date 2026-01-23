<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_requests_id',
        'user_id',
        'company_subscription_id',
        'amount',
        'currency',
        'transaction_id',
        'payment_method',
        'attachment',
        'type',
        'status',
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
        return $this->belongsTo(CompanySubscription::class, 'company_subscription_id');
    }
}
