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
        return $this->morphMany(ActivityLog::class, 'target');
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

    /**
     * Whether the company has an active subscription with at least one verified (completed) payment.
     * Used to allow company admin login and dashboard access.
     */
    public function hasVerifiedSubscription(): bool
    {
        $sub = $this->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->latest()
            ->first();

        if (!$sub) {
            return false;
        }

        return \App\Models\Payment::where('subscription_id', $sub->id)
            ->where('type', 'subscription')
            ->where('status', 'completed')
            ->exists();
    }

    /**
     * Get the current active subscription instance (for package limits).
     */
    public function getActiveSubscription(): ?CompanySubscription
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->with('package')
            ->latest()
            ->first();
    }

    /**
     * Get package limits for this company (from verified active subscription). Returns null if no limits apply.
     *
     * @return array{max_requests_allowed: ?int, max_responses_allowed: ?int}|null
     */
    public function getPackageLimits(): ?array
    {
        $sub = $this->getActiveSubscription();
        if (!$sub || !$sub->package) {
            return null;
        }
        $p = $sub->package;
        if ($p->max_requests_allowed === null && $p->max_responses_allowed === null) {
            return null;
        }
        return [
            'max_requests_allowed' => $p->max_requests_allowed,
            'max_responses_allowed' => $p->max_responses_allowed,
        ];
    }

    /** Count booking requests for this company (for package limit). */
    public function countBookingRequests(): int
    {
        return $this->bookingRequests()->count();
    }

    /** Count booking requests that have an artist assigned (responses) for this company. */
    public function countAssignedResponses(): int
    {
        return $this->bookingRequests()->whereNotNull('assigned_artist_id')->count();
    }

    /** Whether the company can add another booking request (package limit). */
    public function canAddBooking(): bool
    {
        $limits = $this->getPackageLimits();
        if ($limits === null || $limits['max_requests_allowed'] === null) {
            return true;
        }
        return $this->countBookingRequests() < (int) $limits['max_requests_allowed'];
    }

    /** Whether the company can add another artist assignment (package limit). */
    public function canAddArtistResponse(): bool
    {
        $limits = $this->getPackageLimits();
        if ($limits === null || $limits['max_responses_allowed'] === null) {
            return true;
        }
        return $this->countAssignedResponses() < (int) $limits['max_responses_allowed'];
    }

    public function getInitialsAttribute(): string
    {
        $name = trim((string) $this->name);
        if ($name === '') {
            return 'C';
        }

        $parts = preg_split('/\s+/', $name) ?: [];
        $first = strtoupper(substr($parts[0] ?? '', 0, 1));
        $last = count($parts) > 1 ? strtoupper(substr($parts[count($parts) - 1], 0, 1)) : '';

        return $first . $last;
    }
}
