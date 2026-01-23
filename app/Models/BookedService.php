<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookedService extends Model
{
    protected $fillable = [
        'booking_requests_id',
        'service_requested',
        'total_price',
    ];

    public function bookingRequest()
    {
        return $this->belongsTo(BookingRequest::class, 'booking_requests_id');
    }
}
