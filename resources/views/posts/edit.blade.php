@extends('layouts.app')

@section('content')
<h1>Rediģēt Postu</h1>

@include('components.alert')

<form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div>
        <label for="title">Virsraksts:</label><br>
        <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required>
    </div>

    <div>
        <label for="description">Apraksts:</label><br>
        <textarea name="description" id="description">{{ old('description', $post->description) }}</textarea>
    </div>

    <div>
        <label for="visibility">Redzamība:</label><br>
        <select name="visibility" id="visibility" required>
            <option value="public" {{ old('visibility', $post->visibility) == 'public' ? 'selected' : '' }}>Publisks</option>
            <option value="community" {{ old('visibility', $post->visibility) == 'community' ? 'selected' : '' }}>Kopiena</option>
            <option value="private" {{ old('visibility', $post->visibility) == 'private' ? 'selected' : '' }}>Privāts</option>
        </select>
    </div>

    <h3>Esošie faili</h3>
    @if($post->mediaFiles->count())
        <ul>
            @foreach($post->mediaFiles as $file)
                <div>
                    <img src="{{ asset('storage/' . $file->path) }}" alt="Faila attēls" style="max-width:100px;">
                    <label>
                        <input type="checkbox" name="remove_files[]" value="{{ $file->id }}">
                        Dzēst
                    </label>
                </div>
            @endforeach
        </ul>
    @else
        <p>Nav pievienotu failu.</p>
    @endif

    <h3>Pievienot jaunus failus</h3>
    <input type="file" name="files[]" multiple>

    <button type="submit">Saglabāt izmaiņas</button>
</form>
@endsection