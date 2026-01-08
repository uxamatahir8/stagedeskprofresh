<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventType extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'event_type',
        'event_key',
    ];

    public function bookingRequests()
    {
        return $this->hasMany(BookingRequest::class);
    }
}
