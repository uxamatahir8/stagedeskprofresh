<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    //
    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'zipcode',
        'profile_image',
        'about',
        'country_id',
        'state_id',
        'city_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
