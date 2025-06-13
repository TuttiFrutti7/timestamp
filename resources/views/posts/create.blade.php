@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow mt-8">
    <h2 class="text-2xl font-bold mb-6 text-center">Izveidot jaunu ierakstu</h2>
    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div>
            <label for="title" class="block font-semibold mb-1">Virsraksts</label>
            <input id="title" name="title" type="text" value="{{ old('title') }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300"
                   required>
            @error('title')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="description" class="block font-semibold mb-1">Saturs</label>
            <textarea id="description" name="description" rows="5"
                      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300"
                      required>{{ old('description') }}</textarea>
            @error('description')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="visibility" class="block font-semibold mb-1">Redzamība</label>
            <select name="visibility" id="visibility" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Publisks</option>
                <option value="community" {{ old('visibility') == 'community' ? 'selected' : '' }}>Kopiena</option>
                <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Privāts</option>
            </select>
            @error('visibility')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="files" class="block font-semibold mb-1">Faili (bildes/video/audio)</label>
            <input type="file" name="files[]" multiple accept="image/*,video/*,audio/*"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-300">
            @error('files')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold px-6 py-2 rounded shadow">
                Saglabāt
            </button>
        </div>
    </form>
</div>
@endsection