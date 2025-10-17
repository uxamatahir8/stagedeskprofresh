<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    //
    protected $fillable = [
        'name',
        'country_id'
    ];

    public function country()
    {
        return $this->belongsTo(Countries::class);
    }

    public function cities()
    {
        return $this->hasMany(Cities::class);
    }
}
