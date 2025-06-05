@extends('layouts.app')

@section('content')
    <h1>Rediģēt Postu</h1>

    @include('components.alert')

    <form action="{{ route('posts.update', $post) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="content">Saturs:</label><br>
            <textarea name="content" id="content" required>{{ old('content', $post->content) }}</textarea>
        </div>

        <button type="submit">Saglabāt izmaiņas</button>
    </form>
@endsection
