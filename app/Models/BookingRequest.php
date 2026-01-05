<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BookingRequest extends Model
{
    //
    protected $fillable = [
        'user_id',
        'event_type',
        'name',
        'surname',
        'date_of_birth',
        'address',
        'phone',
        'email',
        'start_time',
        'end_time',
        'dos',
        'donts',
        'playlist_spotify',
        'additional_notes',
        'wedding_date',
        'wedding_time',
        'wedding_location',
        'partner_name',
    ];

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

}
