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
        'username',
        'email',
        'auth_method',
        'role',
        'hidden_profile',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function usageLogs()
    {
        return $this->hasMany(UsageLog::class);
    }

    public function timer()
    {
        return $this->hasOne(Timer::class);
    }

    public function communities()
    {
        // withTimestamps() priekš starp tabulas "community_user",
        // lai Laravel automātiski menedžētu tabulas piepildīšanu
        return $this->belongsToMany(Community::class)->withTimestamps();
    }

    // Check if timer can be changed (7-day lock)
    public function canChangeTimer()
    {
        $timer = $this->timer;
        if (!$timer) return true;
        return now()->diffInDays($timer->set_at) >= 7;
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'followed_user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_user_id', 'user_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCommunityAdmin($community)
    {
        //return $community->owner_id === $this->id;
        return $community && $community->owner_id === $this->id;
    }
}
