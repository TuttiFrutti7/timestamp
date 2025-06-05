<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="title">Virsraksts:</label><br>
        <input type="text" name="title" id="title" value="{{ old('title') }}" required>
    </div>

    <div>
        <label for="description">Apraksts:</label><br>
        <textarea name="description" id="description">{{ old('description') }}</textarea>
    </div>

    <div>
        <label for="visibility">Redzamība:</label><br>
        <select name="visibility" id="visibility" required>
            <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Publisks</option>
            <option value="community" {{ old('visibility') == 'community' ? 'selected' : '' }}>Kopiena</option>
            <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Privāts</option>
        </select>
    </div>

    <div>
        <label for="files">Faili (bildes/video/audio):</label><br>
        <input type="file" name="files[]" multiple accept="image/*,video/*,audio/*">
    </div>

    <button type="submit">Saglabāt</button>
</form>