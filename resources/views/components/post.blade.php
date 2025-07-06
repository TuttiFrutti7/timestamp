<div class="bg-white rounded-lg shadow p-6 mb-6 border border-gray-200">
    <div class="flex items-center mb-4">
        @if($post->user && $post->user->profile_image)
            <img src="{{ asset('storage/' . $post->user->profile_image) }}" class="w-10 h-10 rounded-full object-cover mr-3" alt="Profile">
        @else
            <div class="w-10 h-10 rounded-full bg-gray-200 mr-3"></div>
        @endif
        <div>
            <span class="font-semibold text-gray-800">{{ $post->user->username ?? 'Unknown' }}</span>
            <span class="text-gray-400 text-xs ml-2">{{ $post->created_at->diffForHumans() }}</span>
        </div>
    </div>
    <h3 class="text-xl font-bold mb-2">{{ $post->title }}</h3>
    @if($post->image)
        <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="w-full max-h-64 object-cover rounded mb-4">
    @endif
    <p class="text-gray-700 mb-4">{{ $post->body }}</p>
    <div class="text-xs text-gray-400">{{ $post->image }}</div>
    <div class="flex items-center justify-between">
        <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline">Read more</a>
        <span class="text-sm text-gray-500">in 
            @if($post->community)
                <a href="{{ route('communities.show', $post->community) }}" class="hover:underline">{{ $post->community->name }}</a>
            @else
                <span>General</span>
            @endif
        </span>
    </div>
</div>