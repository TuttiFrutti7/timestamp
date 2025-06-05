<div class="post">
    <h3>{{ $post->title }}</h3>
    <p>{{ $post->description }}</p>
    <small>Autors: {{ $post->user->username ?? 'Nezināms' }}</small>

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
</div>