@extends('layouts.app')

@section('content')
    <h1>Posta Skats</h1>

    <p>{{ $post->content }}</p>

    @foreach ($post->mediaFiles as $media)
        @if ($media->type === 'picture')
            <img src="{{ asset('storage/' . $media->path) }}" alt="Attēls" width="400">
        @elseif ($media->type === 'video')
            <video controls width="400">
                <source src="{{ asset('storage/' . $media->path) }}" type="{{ $media->mime_type }}">
            </video>
        @elseif ($media->type === 'audio')
            <audio controls>
                <source src="{{ asset('storage/' . $media->path) }}" type="{{ $media->mime_type }}">
            </audio>
        @endif
    @endforeach

    <div>
        @can('update', $post)
            <a href="{{ route('posts.edit', $post) }}">Rediģēt</a>
        @endcan
        @can('delete', $post)
            <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Tiešām dzēst?')">Dzēst</button>
            </form>
        @endcan
    </div>

    <div>
        <h2 class="text-xl font-bold mb-4">Comments</h2>

        @auth
        <form method="POST" action="{{ route('comments.store', $post) }}">
            @csrf
            <textarea name="content" class="w-full border rounded p-2 mb-2" placeholder="Leave a comment..."></textarea>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Post</button>
        </form>
        @endauth

        @foreach ($post->comments as $comment)
            <x-comment :comment="$comment" />
        @endforeach
    </div>
@endsection
