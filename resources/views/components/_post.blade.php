<div class="post">
    <p>{{ $post->content }}</p>
    <small>Autors: {{ $post->user->name ?? 'Nezināms' }}</small>

    @foreach ($post->mediaFiles as $media)
        @if ($media->type === 'picture')
            <img src="{{ asset('storage/' . $media->path) }}" alt="Attēls" width="300">
        @elseif ($media->type === 'video')
            <video controls width="300">
                <source src="{{ asset('storage/' . $media->path) }}" type="{{ $media->mime_type }}">
            </video>
        @elseif ($media->type === 'audio')
            <audio controls>
                <source src="{{ asset('storage/' . $media->path) }}" type="{{ $media->mime_type }}">
            </audio>
        @endif
    @endforeach

    <a href="{{ route('posts.show', $post) }}">Skatīt</a>
@endforeach