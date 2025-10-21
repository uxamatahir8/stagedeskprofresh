<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogThread extends Model
{
    //
    protected $fillable = [
        'blog_id',
        'user_id',
        'parent_thread_id',
        'comment',
        'status'
    ];


    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parentThread()
    {
        return $this->belongsTo(BlogThread::class, 'parent_thread_id');
    }
}
