<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    protected $fillable = [
        'user_id',
        'refferal_code',
        'refferal_link',
        'commision_rate',
        'total_earning',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commissions()
    {
        return $this->hasMany(AffiliateComission::class);
    }
}
