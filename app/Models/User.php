<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'is_admin',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_admin' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function friendsOfMine()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->wherePivot('status', 'accepted')->withTimestamps();
    }

    public function friendOf()
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
            ->wherePivot('status', 'accepted')->withTimestamps();
    }

    public function getFriendsAttribute()
    {
        return $this->friendsOfMine->merge($this->friendOf);
    }

    public function friendRequests()
    {
        return $this->hasMany(Friend::class, 'friend_id')->where('status', 'pending');
    }

    public function sentFriendRequests()
    {
        return $this->hasMany(Friend::class, 'user_id')->where('status', 'pending');
    }

    public function isFriendsWith(User $user)
    {
        return $this->friends->contains($user);
    }

    public function hasPendingFriendRequestFrom(User $user)
    {
        return $this->friendRequests()->where('user_id', $user->id)->exists();
    }

    public function hasSentFriendRequestTo(User $user)
    {
        return $this->sentFriendRequests()->where('friend_id', $user->id)->exists();
    }

    public function getFriendship(User $user)
    {
        return Friend::where(function ($query) use ($user) {
            $query->where('user_id', $this->id)->where('friend_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('friend_id', $this->id);
        })->first();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function hasUnreadMessagesFrom(User $user)
    {
        return Message::where('sender_id', $user->id)
            ->where('receiver_id', $this->id)
            ->whereNull('read_at')
            ->exists();
    }

    public function getConversationsAttribute()
    {
        $sent = $this->sentMessages()->with('receiver')->get()->pluck('receiver');
        $received = $this->receivedMessages()->with('sender')->get()->pluck('sender');

        return $sent->merge($received)->unique('id')->sortByDesc(function ($user) {
            $lastMessage = Message::where(function ($query) use ($user) {
                $query->where('sender_id', $this->id)
                    ->where('receiver_id', $user->id);
            })->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $this->id);
            })->latest()->first();
            return $lastMessage->created_at;
        });
    }

    public function watchedMovies()
    {
        return $this->hasMany(WatchedMovie::class);
    }

    public function ratedMovies()
    {
        return $this->belongsToMany(Movie::class, 'watched_movies')->withPivot('rating')->withTimestamps();
    }
}
