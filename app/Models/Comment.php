<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'content',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Parent comment (dành cho replies)
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Child comments (replies)
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user', 'replies');
    }

    // Top-level comments only
    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }
}
