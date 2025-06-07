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

        <div x-data="commentSection({{ $post->id }}, '{{ route('posts.comments', $post) }}')">
            <form @submit.prevent="submitNewComment">
                <textarea x-model="newComment" class="w-full border p-2 rounded"></textarea>
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 mt-2 rounded">Post</button>
            </form>

            <div id="comments-list">
                @php
                    $comments = $post->comments()->latest()->paginate(10);
                @endphp
                @foreach ($comments as $comment)
                    @include('partials.comment', ['comment' => $comment])
                @endforeach
            </div>

            @if ($comments->hasMorePages())
                <button
                    x-show="nextPage"
                    @click="loadMoreComments"
                    class="mt-4 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                >
                    Load More Comments
                </button>
            @endif
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('commentSection', (postId, baseUrl) => ({
                    newComment: '',
                    nextPage: baseUrl + '?page=2',

                    async submitNewComment() {
                        const response = await fetch(`/posts/${postId}/comments`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ content: this.newComment })
                        });

                        if (response.ok) {
                            const data = await response.json();
                            document.getElementById('comments-list').insertAdjacentHTML('afterbegin', data.html);
                            Alpine.initTree(document.getElementById('comments-list'));
                            this.newComment = '';
                        } else {
                            alert('Failed to post comment');
                        }
                    },


                    async loadMoreComments() {
                        if (!this.nextPage) return;

                        const response = await fetch(this.nextPage, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            document.getElementById('comments-list').insertAdjacentHTML('afterbegin', data.html);
                            this.nextPage = data.nextPage;
                        } else {
                            alert('Failed to load more comments');
                        }
                    }
                }));
            });
        </script>

    </div>
@endsection