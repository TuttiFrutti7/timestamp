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
</script>