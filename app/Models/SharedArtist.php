<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedArtist extends Model
{
    protected $fillable = [
        'artist_id',
        'owner_company_id',
        'shared_with_company_id',
        'status',
        'notes',
        'shared_at',
        'accepted_at',
        'revoked_at'
    ];

    protected $casts = [
        'shared_at' => 'datetime',
        'accepted_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function ownerCompany()
    {
        return $this->belongsTo(Company::class, 'owner_company_id');
    }

    public function sharedWithCompany()
    {
        return $this->belongsTo(Company::class, 'shared_with_company_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'accepted')->whereNull('revoked_at');
    }
}
