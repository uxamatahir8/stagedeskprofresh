<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'blog_id', 'parent_id', 'content', 'status', 'likes_count'];

    protected $casts = [
        'likes_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->where('status', 'published')->with('user', 'replies')->latest();
    }

    public function allReplies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user', 'allReplies');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUnapproved($query)
    {
        return $query->where('status', 'unapproved');
    }
}
