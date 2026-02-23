<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistWithdrawalRequest extends Model
{
    protected $fillable = [
        'artist_id',
        'company_id',
        'amount',
        'status',
        'artist_notes',
        'admin_notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
