<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtistRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_requests_id',
        'artist_id',
        'proposed_price',
        'message',
        'status',
    ];

    public function bookingRequest()
    {
        return $this->belongsTo(BookingRequest::class, 'booking_requests_id');
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
