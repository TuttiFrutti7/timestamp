<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'owner_id',
    ];

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
    
    public function owner()
    {
        return $this->belongsTo(\App\Models\User::class, 'owner_id');
    }
}
