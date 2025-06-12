@extends('layouts.app')

@section('content')
    <h1>Kopienas posti</h1>

    @foreach ($posts as $post)
        @include('components._post', ['post' => $post])
    @endforeach
@endsection
