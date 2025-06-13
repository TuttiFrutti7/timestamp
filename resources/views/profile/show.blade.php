@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow mt-8">
    <div class="flex flex-col items-center">
        @if($user->profile_image)
            <img src="{{ asset('storage/' . $user->profile_image) }}" class="w-32 h-32 rounded-full object-cover mb-4">
        @else
            <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 mb-4">
                No Image
            </div>
        @endif
        <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
        <div class="text-gray-500 mb-4">@{{ $user->username }}</div>
        <div class="flex gap-8 mb-4">
            <span class="font-bold">{{ $user->followers()->count() }}</span> Followers
            <span class="font-bold">{{ $user->following()->count() }}</span> Following
        </div>
        <div class="w-full">
            <h3 class="font-semibold mt-6 mb-2">Followers:</h3>
            <ul>
                @forelse($user->followers as $follower)
                    <li class="flex items-center gap-2 mb-2">
                        @if($follower->profile_image)
                            <img src="{{ asset('storage/' . $follower->profile_image) }}" class="w-6 h-6 rounded-full object-cover">
                        @endif
                        <a href="{{ route('users.show', $follower) }}" class="text-blue-600 hover:underline">{{ $follower->username }}</a>
                    </li>
                @empty
                    <li class="text-gray-500">No followers yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection