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

    public function scopeCompanyArtists($query)
    {
        return $query->where('company_id', Auth::user()->company_id);
    }

    public function scopeActive($query)
    {
        return $query->where('deleted_at', null);
    }
}
