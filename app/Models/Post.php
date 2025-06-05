<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'community_id',
        'visibility',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function mediaFiles()
    {
        return $this->hasMany(MediaFile::class);
    }

    protected static function booted()
    {
        static::deleting(function ($post) {
            foreach ($post->mediaFiles as $media) {
                Storage::disk('public')->delete($media->path);
            }
        });
    }

    public function deleteMediaFiles(array $fileIds)
    {
        $files = $this->mediaFiles()->whereIn('id', $fileIds)->get();

        foreach ($files as $file) {
            Storage::disk('public')->delete($file->path);
            $file->delete();
        }
    }

    public function addMediaFiles(array $uploadedFiles)
    {
        foreach ($uploadedFiles as $file) {
            $path = $file->store('media', 'public');
            $mime = $file->getMimeType();

            $type = match (true) {
                str_starts_with($mime, 'image') => 'picture',
                str_starts_with($mime, 'video') => 'video',
                str_starts_with($mime, 'audio') => 'audio',
                default => 'text',
            };

            $this->mediaFiles()->create([
                'type' => $type,
                'mime_type' => $mime,
                'path' => $path,
            ]);
        }
    }
}
