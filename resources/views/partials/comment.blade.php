@props(['comment'])
<div x-data="commentEdit({{ $comment->id }}, @js($comment->content), @js($comment->created_at), @js($comment->updated_at))" id="comment-{{ $comment->id }}">
    <template x-if="!editing">
        <div>
            <p class="text-sm text-gray-700"><strong>{{ $comment->user->username }}</strong> said:</p>
            <p x-text="editedContent"></p>
            <p class="text-xs text-gray-500">
                {{ $comment->created_at->diffForHumans() }}
                <template x-if="wasEdited">
                    · <em>(edited)</em>
                </template>
            </p>
            @can('update', $comment)
                <button @click="editing = true" class="text-blue-500 text-sm">Edit</button>
            @endcan
            @can('delete', $comment)
                <button @click="deleteComment" class="text-red-500 text-sm">Delete</button>
            @endcan
        </div>
    </template>
    <template x-if="editing">
        <div>
            <textarea x-model="editedContent" class="w-full border p-2 rounded"></textarea>
            <div class="flex gap-2 mt-2">
                <button @click="submitEdit" class="bg-green-500 text-white px-3 py-1 rounded">Save</button>
                <button type="button" @click="cancelEdit" class="bg-gray-300 text-black px-3 py-1 rounded">Cancel</button>
            </div>
        </div>
    </template>
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
</script>