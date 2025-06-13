@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Search</h1>
    <form action="{{ route('search') }}" method="GET" class="mb-6">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search for users or communities..." class="border rounded px-3 py-2 w-1/2">
        <button type="submit" class="bg-yellow-300 px-4 py-2 rounded ml-2">Search</button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h2 class="text-xl font-semibold mb-2">Users</h2>
            @forelse($users as $user)
                <div class="flex items-center justify-between p-2 border-b">
                    <a href="{{ route('users.show', $user) }}" class="text-blue-600 hover:underline">
                        {{ $user->username }}
                    </a>
                    @auth
                        @if(auth()->user()->id !== $user->id)
                            @if(auth()->user()->following->contains($user->id))
                                <form action="{{ route('users.unfollow', $user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500">Unfollow</button>
                                </form>
                            @else
                                <form action="{{ route('users.follow', $user) }}" method="POST">
                                    @csrf
                                    <button class="text-blue-500">Follow</button>
                                </form>
                            @endif
                        @endif
                    @endauth
                </div>
            @empty
                <div class="text-gray-500">No users found.</div>
            @endforelse
        </div>
        <div>
            <h2 class="text-xl font-semibold mb-2">Communities</h2>
            @forelse($communities as $community)
                <div class="p-2 border-b">
                    <a href="{{ route('communities.show', $community) }}" class="text-blue-600 hover:underline">{{ $community->name }}</a>
                </div>
            @empty
                <div class="text-gray-500">No communities found.</div>
            @endforelse
        </div>
    </div>
@endsection