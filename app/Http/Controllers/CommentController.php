<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = new Comment([
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        $post->comments()->save($comment);

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view('partials.comment', ['comment' => $comment])->render()
            ]);
        }


        return redirect()->route('posts.show', $post)->with('success', 'Comment added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);
        return view('comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Comment updated!']);
        }

        return redirect()->route('posts.show', $comment->post_id)->with('success', 'Comment updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment, Request $request)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Comment deleted']);
        }

        return redirect()->back()->with('success', 'Comment deleted!');
    }

    // To make only 10 comments load untill presses the button 
    public function comments(Post $post, Request $request)
    {
        $comments = $post->comments()->latest()->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view('partials.comments', ['comments' => $comments])->render(),
                'nextPage' => $comments->nextPageUrl()
            ]);
        }

        return redirect()->route('posts.show', $post);
    }

}
