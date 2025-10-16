<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    //

    protected $fillable = ['handle', 'url', 'user_id', 'company_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}