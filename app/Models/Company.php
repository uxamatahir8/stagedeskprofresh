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
        'logo'
    ];

    public function socialLinks()
    {
        return $this->hasMany(SocialLink::class, 'company_id');
    }
}