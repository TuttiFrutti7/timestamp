@extends('layouts.app')

@section('content')
    @include('components.alert')

    @foreach ($posts as $post)
        @include('components._post', ['post' => $post])
    @endforeach
@endsection