<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'type',
        'mime_type',
        'path',
    ];


    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
