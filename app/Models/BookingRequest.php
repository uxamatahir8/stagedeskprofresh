<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingRequest extends Model
{
    //
    protected $fillable = [
        'tracking_code',
        'user_id',
        'company_id',
        'assigned_artist_id',
        'status',
        'payment_status',
        'event_type_id',
        'name',
        'surname',
        'date_of_birth',
        'address',
        'phone',
        'email',
        'event_date',
        'start_time',
        'end_time',
        'opening_songs',
        'special_moments',
        'dos',
        'donts',
        'playlist_spotify',
        'additional_notes',
        'company_notes',
        'wedding_date',
        'wedding_time',
        'wedding_location',
        'partner_name',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
    ];

    protected static function booted()
    {
        static::creating(function (self $booking) {
            if (empty($booking->tracking_code)) {
                $booking->tracking_code = self::generateUniqueTrackingCode();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'tracking_code';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (($field ?? $this->getRouteKeyName()) === 'tracking_code') {
            return $this->newQuery()
                ->where('tracking_code', $value)
                ->orWhere('id', $value)
                ->firstOrFail();
        }

        return parent::resolveRouteBinding($value, $field);
    }

    public function getDisplayIdAttribute(): string
    {
        return (string) ($this->tracking_code ?: $this->id);
    }

    public function getCustomerInitialsAttribute(): string
    {
        $first = strtoupper(substr(trim((string) $this->name), 0, 1));
        $last = strtoupper(substr(trim((string) $this->surname), 0, 1));

        return $first . $last;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUserBookings($query)
    {
        return $query->where('user_id', Auth::user()->id);
    }

    public function scopeCompanyBookings($query)
    {
        $user = Auth::user();

        if (! $user || $user->role->role_key !== 'company_admin') {
            return $query->whereRaw('1 = 0'); // deny access safely
        }

        return $query->whereHas('user', function ($q) use ($user) {
            $q->where('company_id', $user->company_id)
                ->whereHas('role', function ($roleQuery) {
                    $roleQuery->where('role_key', 'customer');
                });
        });
    }

    public function eventType()
    {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function assignedArtist()
    {
        return $this->belongsTo(Artist::class, 'assigned_artist_id');
    }

    public function bookedServices()
    {
        return $this->hasMany(BookedService::class, 'booking_requests_id');
    }

    public function artistRequests()
    {
        return $this->hasMany(ArtistRequest::class, 'booking_requests_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_requests_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'booking_id');
    }

    /**
     * Check if booking can be reviewed
     */
    public function canBeReviewed(): bool
    {
        return $this->status === 'completed' &&
               !$this->reviews()->where('user_id', Auth::user()->id)->exists();
    }

    private static function generateUniqueTrackingCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('tracking_code', $code)->exists());

        return $code;
    }

}
