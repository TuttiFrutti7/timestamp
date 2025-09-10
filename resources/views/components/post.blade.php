{{-- This outer div now controls the width of EACH post --}}
<div class="bg-white rounded-lg shadow p-6 mb-6 border border-gray-200 mx-auto max-w-xl">
    <div class="flex items-center mb-4">
        @if($post->user && $post->user->profile_image)
            <img src="{{ asset('storage/' . $post->user->profile_image) }}" class="w-8 h-8 rounded-full object-cover mr-3" alt="Profile">
        @else
            <div class="w-10 h-10 rounded-full bg-gray-200 mr-3"></div>
        @endif
        <div>
            <span class="font-semibold text-gray-800">{{ $post->user->username ?? 'Unknown' }}</span>
            <span class="text-gray-400 text-xs ml-2">{{ $post->created_at->diffForHumans() }}</span>
        </div>
    </div>
    <h3 class="text-xl font-bold mb-2">{{ $post->title }}</h3>

    {{-- Check if mediaFiles collection is not empty --}}
    @if($post->mediaFiles->isNotEmpty())
        {{-- Get the first media file --}}
        @php
            $firstMedia = $post->mediaFiles->first();
        @endphp

        {{-- Container for all visual media types (picture, video) --}}
        @if(in_array($firstMedia->type, ['picture', 'video']))
            {{-- CRITICAL CHANGE: Inline width and height to force dimensions --}}
            <div style="width: 256px; height: 256px; background-color: black; position: relative;"
                 class="flex items-center justify-center rounded overflow-hidden mb-4">
                @if($firstMedia->type === 'picture')
                    <img
                        src="{{ asset('storage/' . $firstMedia->path) }}"
                        alt="Post Image"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;"
                    />
                @elseif($firstMedia->type === 'video')
                    <video
                        controls
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;"
                        preload="metadata"
                        disablePictureInPicture
                        controlsList="nodownload nofullscreen noremoteplayback"
                        poster="{{ asset('images/video_placeholder.png') }}" {{-- Optional placeholder --}}
                    >
                        <source src="{{ asset('storage/' . $firstMedia->path) }}" type="{{ $firstMedia->mime_type }}">
                        Your browser does not support the video tag.
                    </video>
                @endif
            </div>
        @elseif($firstMedia->type === 'audio')
            {{-- Audio still has a different desired height --}}
            <div class="w-64 h-20 flex items-center bg-gray-200 rounded px-2 mb-4">
                <audio controls class="w-full">
                    <source src="{{ asset('storage/' . $firstMedia->path) }}" type="{{ $firstMedia->mime_type }}">
                    Your browser does not support the audio element.
                </audio>
            </div>
        @endif
    @endif

    <p class="text-gray-700 mb-4">{{ $post->description }}</p>
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