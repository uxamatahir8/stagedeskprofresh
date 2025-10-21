<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    //
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'blog_category_id',
        'user_id',
        'status',
        'feature_image',
        'published_at'
    ];


    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(BlogThread::class);
    }
}
