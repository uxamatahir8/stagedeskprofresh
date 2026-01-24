<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'website',
        'kvk_number',
        'contact_name',
        'contact_phone',
        'contact_email',
        'status',
        'address',
        'logo',
        'email_verified_at',
        'is_verified',
        'verified_at',
        'last_login_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function socialLinks()
    {
        return $this->hasMany(SocialLink::class, 'company_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(CompanySubscription::class);
    }

    public function artists()
    {
        return $this->hasMany(Artist::class);
    }

    public function bookings()
    {
        return $this->hasMany(BookingRequest::class);
    }

    public function bookingRequests()
    {
        return $this->hasMany(BookingRequest::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    /**
     * Get the active subscription
     */
    public function activeSubscription()
    {
        return $this->hasOne(CompanySubscription::class)
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->latest();
    }
}
