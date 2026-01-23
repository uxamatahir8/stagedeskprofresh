<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'booking_id',
        'artist_id',
        'company_id',
        'rating',
        'review',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    /**
     * Get the user who wrote the review
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the booking this review is for
     */
    public function booking()
    {
        return $this->belongsTo(BookingRequest::class, 'booking_id');
    }

    /**
     * Get the artist being reviewed
     */
    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    /**
     * Get the company being reviewed
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope to get only approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get featured reviews
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
