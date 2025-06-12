@extends('layouts.app')

@section('content')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
        Profile
    </h2>

    <div class="space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>

    @if($user->profile_image)
        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" class="w-32 h-32 rounded-full object-cover">
    @else
        <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
            No Image
        </div>
    @endif
@endsection
