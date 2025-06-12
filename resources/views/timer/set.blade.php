@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Set Your Daily Limit</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('timer.set') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="limit">Daily limit (in minutes)</label>
            <input type="number" name="limit" id="limit" class="form-control" min="1" max="60" value="{{ old('limit', auth()->user()->timer->limit ?? '') }}" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Save Limit</button>
    </form>
</div>
@endsection