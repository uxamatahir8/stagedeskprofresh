<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'max_users_allowed',
        'max_requests_allowed',
        'max_responses_allowed',
        'status',
    ];


    public function companySubscription()
    {
        return $this->hasMany(CompanySubscription::class);
    }

    public function features()
    {
        return $this->hasMany(PackageFeatures::class);
    }
}
