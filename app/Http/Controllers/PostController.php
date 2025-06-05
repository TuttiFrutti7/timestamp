<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Izvadīt visus Postus
    public function index()
    {
        $posts = Post::latest()->get(); // TODO: (VARBŪT) Implementē postu izvedi vairākās lapās
        return view('posts.index', compact('posts'));
    }

    // Izvadīt formu izveidot jaunu postu
    public function create()
    {
        return view('posts.create');
    }

    // uzglabāt jaunu postu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'community_id' => 'nullable|exists:communities,id',
            'files.*' => 'file|max:20480' // atļauj vairākus failus
        ]);

        $post = new Post($validated);
        $post->user_id = Auth::id();
        $post->save();

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('media', 'public');
                $mime = $file->getMimeType();
                $type = match (true) {
                    str_starts_with($mime, 'image') => 'picture',
                    str_starts_with($mime, 'video') => 'video',
                    str_starts_with($mime, 'audio') => 'audio',
                    default => 'text',
                };

                MediaFile::create([
                    'post_id' => $post->id,
                    'type' => $type,
                    'mime_type' => $mime,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('posts.index')->with('success', 'Post created!');
    }

    // Izvada konkrētu postu
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    // Izvada formu rediģēt postu
    public function edit(Post $post)
    {
        $this->authorize('update', $post);  // Optional: policy
        return view('posts.edit', compact('post'));
    }

    // Atjaunina konkrētu postu
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);  // Optional: policy

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post->update($validated);
        return redirect()->route('posts.show', $post)->with('success', 'Post updated!');
    }

    // Izdzēš konkrētu postu
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);  // Optional: policy

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted!');
    }
}
