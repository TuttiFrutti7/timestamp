@extends('layouts.app')

@section('content')
    <h1 class="text-x1 font-bold mb-5">{{ $post->title }}</h1>

    <p>{{ $post->description }}</p>

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
                <button type="submit"
                    class="bg-blue-500 text-white px-3 py-1 mt-2 rounded"
                    :disabled="loading">
                Post
                </button>
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
            function commentEdit(id, content, createdAt = null, updatedAt = null) {
                return {
                    editing: false,
                    editedContent: content,
                    originalContent: content,
                    wasEdited: updatedAt && createdAt ? updatedAt !== createdAt : false,
                    async submitEdit() {
                        const response = await fetch(`/comments/${id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ content: this.editedContent })
                        });
                        if (response.ok) {
                            const data = await response.json();
                            this.editing = false;
                            this.editedContent = data.content;
                            this.wasEdited = data.wasEdited;
                        } else {
                            alert('Failed to update comment');
                        }
                    },
                    cancelEdit() {
                        this.editedContent = this.originalContent;
                        this.editing = false;
                    },
                    deleteComment() {
                        if (!confirm('Delete this comment?')) return;
                        fetch(`/comments/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        }).then(response => {
                            if (response.ok) {
                                document.getElementById(`comment-${id}`).remove();
                            } else {
                                alert('Failed to delete comment');
                            }
                        });
                    }
                }
            }

            document.addEventListener('alpine:init', () => {
                Alpine.data('commentSection', (postId, baseUrl) => ({
                    newComment: '',
                    loading: false,
                    nextPage: baseUrl + '?page=2',

                    async submitNewComment() {
                        if (this.loading) return;
                        this.loading = true;

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
                            const commentList = document.getElementById('comments-list');

                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = data.html.trim();
                            const newCommentElem = tempDiv.firstElementChild;

                            commentList.insertBefore(newCommentElem, commentList.firstChild);

                            Alpine.initTree(newCommentElem);

                            this.newComment = '';
                        }
                        this.loading = false;
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
                            document.getElementById('comments-list').insertAdjacentHTML('beforeend', data.html);
                            this.nextPage = data.nextPage;
                        }
                    }
                }));
            });
        </script>

    </div>
@endsection