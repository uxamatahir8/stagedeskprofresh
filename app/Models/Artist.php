<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Artist extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
        'stage_name',
        'experience_years',
        'genres',
        'specialization',
        'rating',
        'image',
        'bio',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function artistRequests()
    {
        return $this->hasMany(ArtistRequest::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function assignedBookings()
    {
        return $this->hasMany(BookingRequest::class, 'assigned_artist_id');
    }

    public function sharedWith()
    {
        return $this->hasMany(SharedArtist::class, 'artist_id');
    }

    public function sharedWithCompanies()
    {
        return $this->belongsToMany(Company::class, 'shared_artists', 'artist_id', 'shared_with_company_id')
            ->withPivot('status', 'notes', 'shared_at', 'accepted_at', 'revoked_at')
            ->withTimestamps();
    }

    public function scopeCompanyArtists($query)
    {
        return $query->where('company_id', Auth::user()->company_id);
    }

    public function scopeActive($query)
    {
        return $query->where('deleted_at', null);
    }
}
