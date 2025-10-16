<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySubscription extends Model
{
    //
    protected $fillable = ['company_id', 'package_id', 'start_date', 'end_date', 'status'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
