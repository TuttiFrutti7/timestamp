@extends('layouts.app')

@section('content')
    <h1>Visi posti</h1>

    @include('components.alert')

    @foreach ($posts as $post)
        @include('components._post', ['post' => $post])
    @endforeach
@endsection