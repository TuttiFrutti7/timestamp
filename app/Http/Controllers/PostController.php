<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;
    // Izvadīt visus Postus
    public function index(Request $request)
    {   
        $posts = Post::with(['mediaFiles', 'user'])->latest()->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view('partials.posts', ['posts' => $posts])->render(),
                'nextPage' => $posts->nextPageUrl()
            ]);
        }

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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'community_id' => 'nullable|exists:communities,id',
            'visibility' => 'required|in:public,community,private',
            'files.*' => 'file|max:20480|mimetypes:image/jpeg,image/png,image/webp,video/mp4,audio/mpeg,audio/wav',
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
        $this->authorize('view', $post); // This checks the PostPolicy's 'view' method

        return view('posts.show', compact('post'));
    }

    // Izvada formu rediģēt postu
    public function edit(Post $post)
    {
        $this->authorize('update', $post);  // Optional: policy
        return view('posts.edit', compact('post'));
    }
    // Izvada visus postus ar 'public' redzamību
    public function community(Request $request)
    {
        $user = $request->user();

        // Get IDs of communities the user has joined
        $communityIds = $user->communities()->pluck('communities.id');

        // Get posts from those communities with 'community' visibility
        $posts = Post::where('visibility', 'community')
            ->whereIn('community_id', $communityIds)
            ->latest()
            ->get();

        return view('posts.community', compact('posts'));
    }


    // Atjaunina konkrētu postu
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);  //policy
    
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'required|in:public,community,private',
            'files.*' => 'file|max:20480|mimetypes:image/jpeg,image/png,image/webp,video/mp4,audio/mpeg,audio/wav',
            'remove_files' => 'array',
            'remove_files.*' => 'integer|exists:media_files,id',
        ]);

        $post->update($validated);

        if ($request->filled('remove_files')) {
            $post->deleteMediaFiles($request->remove_files);
        }

        if ($request->hasFile('files')) {
            $post->addMediaFiles($request->file('files'));
        }

        return redirect()->route('posts.show', $post)->with('success', 'Post updated!');
    }

    // Izdzēš konkrētu postu
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post); //policy

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted!');
    }
}
