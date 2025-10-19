<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    //

    protected $fillables = [
        'name',
        'state_id'
    ];

    public function state()
    {
        return $this->belongsTo(States::class);
    }
}