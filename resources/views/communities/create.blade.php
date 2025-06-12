<form action="{{ route('communities.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Community name" required>
    <textarea name="description" placeholder="Description"></textarea>
    <select name="type" required>
        <option value="public">Public</option>
        <option value="private">Private</option>
        <option value="hidden">Hidden</option>
    </select>
    <button type="submit">Create Community</button>
</form>