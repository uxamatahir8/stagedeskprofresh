<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistEarning extends Model
{
    protected $fillable = [
        'artist_id',
        'booking_request_id',
        'payment_id',
        'amount',
        'amount_paid_out',
        'share_percentage',
        'status',
        'paid_out_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_paid_out' => 'decimal:2',
        'share_percentage' => 'decimal:2',
        'paid_out_at' => 'datetime',
    ];

    public function getAvailableAmountAttribute(): float
    {
        return (float) $this->amount - (float) ($this->amount_paid_out ?? 0);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function bookingRequest()
    {
        return $this->belongsTo(BookingRequest::class, 'booking_request_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public static function getAvailableBalanceForArtist(int $artistId): float
    {
        return (float) self::where('artist_id', $artistId)
            ->where('status', 'available')
            ->get()
            ->sum(fn ($e) => (float) $e->amount - (float) ($e->amount_paid_out ?? 0));
    }
}
