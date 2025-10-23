<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonials extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name',
        'avatar',
        'testimonial',
        'designation'
    ];
}
