{{-- filepath: resources/views/communities/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>{{ $community->name }}</h1>
    <p>{{ $community->description }}</p>
    <p>Type: {{ ucfirst($community->type) }}</p>
    <p>Owner: {{ $community->owner->name ?? 'Unknown' }}</p>

    @auth
        @if(auth()->user()->communities->contains($community))
            <form action="{{ route('communities.leave', $community) }}" method="POST">
                @csrf
                <button type="submit">Leave Community</button>
            </form>
        @else
            <form action="{{ route('communities.join', $community) }}" method="POST">
                @csrf
                <button type="submit">Join Community</button>
            </form>
        @endif
    @endauth

    {{-- Add more details or actions as needed --}}
@endsection