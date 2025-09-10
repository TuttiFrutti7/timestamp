<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MediaFile;

class MediaController extends Controller
{
    public function index()
    {
        //
    }

    // Saglabāt jaunu failu
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'file' => 'required|file|max:100000', // 100MB
        ]);

        $file = $request->file('file');
        $path = $file->store('media', 'public');

        $mime = $file->getMimeType();
        $type = match (true) {
            str_starts_with($mime, 'image') => 'picture',
            str_starts_with($mime, 'video') => 'video',
            str_starts_with($mime, 'audio') => 'audio',
            default => 'text',
        };

        MediaFile::create([
            'post_id' => $request->post_id,
            'type' => $type,
            'mime_type' => $mime,
            'path' => $path,
        ]);

        return back()->with('success', 'File uploaded!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
