<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Community extends Model
{
    use HasFactory;

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function users()
    {
        // withTimestamps() priekš starp tabulas "community_user",
        // lai Laravel automātiski menedžētu tabulas piepildīšanu
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
