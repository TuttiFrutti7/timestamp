@extends('layouts.app')

@section('content')
    <h1>Izveidot Postu</h1>

    @include('components.alert')

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="content">Saturs:</label><br>
            <textarea name="content" id="content" required>{{ old('content') }}</textarea>
        </div>

        <div>
            <label for="files">Faili (bildes/video/audio):</label><br>
            <input type="file" name="files[]" multiple>
        </div>

        <button type="submit">Saglabāt</button>
    </form>
@endsection
