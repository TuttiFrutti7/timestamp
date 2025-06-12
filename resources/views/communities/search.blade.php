<form action="{{ route('communities.search') }}" method="GET">
    <input type="text" name="q" placeholder="Search communities..." value="{{ request('q') }}">
    <button type="submit">Search</button>
</form>

<ul>
@foreach($communities as $community)
    <div>
        <a href="{{ route('communities.show', $community) }}">
            {{ $community->name }}
        </a>
        @auth
            @if(auth()->user()->communities->contains($community))
                <form action="{{ route('communities.leave', $community) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit">Leave</button>
                </form>
            @else
                <form action="{{ route('communities.join', $community) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit">Join</button>
                </form>
            @endif
        @endauth
    </div>
@endforeach
</ul>