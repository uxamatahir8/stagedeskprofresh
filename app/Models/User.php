<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'company_id',
        'name',
        'email',
        'password',
        'status',
        'locked_until',
        'failed_login_attempts',
        'last_login_at',
        'last_login_ip',
        'password_changed_at',
        'force_password_change',
        'email_verified_at',
        'verification_token',
        'must_change_password',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'locked_until'      => 'datetime',
            'last_login_at'     => 'datetime',
            'password_changed_at' => 'datetime',
            'force_password_change' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function scopeCompanyUsers($query)
    {
        return $query->where('company_id', Auth::user()->company_id);
    }

    public function scopeCompanyCustomers($query)
    {
        return $query->where('company_id', Auth::user()->company_id)
            ->whereHas('role', function ($q) {
                $q->where('role_key', 'customer');
            });
    }

    public function scopeCompanyArtists($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('role_key', 'artist');
        })->where('company_id', Auth::user()->company_id);
    }

    public function scopeAllCustomers($query)
    {
        return $query->whereHas('role', function ($q) {
            $q->where('role_key', 'customer');
        });
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function twoFactorAuth()
    {
        return $this->hasOne(TwoFactorAuth::class);
    }

    public function loginAttempts()
    {
        return $this->hasMany(LoginAttempt::class);
    }

    public function securityLogs()
    {
        return $this->hasMany(SecurityLog::class);
    }

    public function passwordHistory()
    {
        return $this->hasMany(PasswordHistory::class);
    }

    public function artist()
    {
        return $this->hasOne(Artist::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function bookingRequests()
    {
        return $this->hasMany(BookingRequest::class);
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }
}
