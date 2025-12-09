<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'user_id',
        'image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likers()
    {
        return $this->belongsToMany(User::class, 'likes', 'post_id', 'user_id')->withTimestamps();
    }

    public function likedBy(User $user)
    {
        return $this->likers->contains($user);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
