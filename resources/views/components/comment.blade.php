@props(['comment'])

<div x-data="{ editing: false, editedContent: @js($comment->content) }" class="border rounded p-3 my-2">
    <template x-if="!editing">
        <div>
            <p class="text-sm text-gray-700"><strong>{{ $comment->user->username }}</strong> said:</p>
            <p>{{ $comment->content }}</p>
            <p class="text-xs text-gray-500">
                {{ $comment->created_at->diffForHumans() }} 
                @if ($comment->created_at != $comment->updated_at)
                    · <em>(edited)</em>
                @endif
            </p>

            @can('update', $comment)
                <button @click="editing = true" class="text-blue-500 text-sm mr-2">Edit</button>
            @endcan

            @can('delete', $comment)
                <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this comment?')" class="text-red-500 text-sm">Delete</button>
                </form>
            @endcan
        </div>
    </template>

    <template x-if="editing">
        <form method="POST" action="{{ route('comments.update', $comment) }}" class="space-y-2">
            @csrf
            @method('PUT')
            <textarea name="content" x-model="editedContent" class="w-full border p-2 rounded"></textarea>
            <div class="flex gap-2">
                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">Save</button>
                <button type="button" @click="editing = false" class="bg-gray-300 text-black px-3 py-1 rounded">Cancel</button>
            </div>
        </form>
    </template>
</div>