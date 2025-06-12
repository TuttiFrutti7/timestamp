<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="flex-1 space-y-6">
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div>
                            <p class="text-sm mt-2 text-gray-800">
                                {{ __('Your email address is unverified.') }}

                                <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div>
                    <x-input-label for="username" :value="__('Username')" />
                    <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autofocus autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('username')" />
                </div>

                <div>
                    <x-input-label for="profile_image" :value="__('Profile Image')" />
                    <input id="profile_image" name="profile_image" type="file" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('profile_image')" />
                </div>
            </div>
            <div class="flex-shrink-0">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" class="w-32 h-32 rounded-full object-cover">
                @else
                    <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                        No Image
                    </div>
                @endif
            </div>
        </div>

        <div class="flex gap-8 mb-6">
            <button type="button" 
                class="text-blue-600 hover:underline focus:outline-none"
                x-data
                @click="$dispatch('show-followers')">
                <span class="font-bold">{{ $user->followers()->count() }}</span> Followers
            </button>
            <button type="button" 
                class="text-blue-600 hover:underline focus:outline-none"
                x-data
                @click="$dispatch('show-following')">
                <span class="font-bold">{{ $user->following()->count() }}</span> Following
            </button>
        </div>

        <div class="flex items-center gap-4 mt-6">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

<!-- Followers Popup -->
<div 
    x-data="{ open: false }"
    x-on:show-followers.window="open = true"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
    style="display: none;"
>
    <div class="bg-white rounded-lg shadow-lg p-6 w-80 max-h-[70vh] overflow-y-auto">
        <h3 class="text-lg font-bold mb-4">Followers</h3>
        <ul>
            @forelse($user->followers as $follower)
                <li class="mb-2 flex items-center gap-2">
                    @if($follower->profile_image)
                        <img src="{{ asset('storage/' . $follower->profile_image) }}" class="w-6 h-6 rounded-full object-cover">
                    @endif
                    <span>{{ $follower->username }}</span>
                </li>
            @empty
                <li class="text-gray-500">No followers yet.</li>
            @endforelse
        </ul>
        <button class="mt-4 px-4 py-2 bg-gray-200 rounded" @click="open = false">Close</button>
    </div>
</div>

<!-- Following Popup -->
<div 
    x-data="{ open: false }"
    x-on:show-following.window="open = true"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
    style="display: none;"
>
    <div class="bg-white rounded-lg shadow-lg p-6 w-80 max-h-[70vh] overflow-y-auto">
        <h3 class="text-lg font-bold mb-4">Following</h3>
        <ul>
            @forelse($user->following as $followed)
                <li class="mb-2 flex items-center gap-2">
                    @if($followed->profile_image)
                        <img src="{{ asset('storage/' . $followed->profile_image) }}" class="w-6 h-6 rounded-full object-cover">
                    @endif
                    <span>{{ $followed->username }}</span>
                </li>
            @empty
                <li class="text-gray-500">Not following anyone yet.</li>
            @endforelse
        </ul>
        <button class="mt-4 px-4 py-2 bg-gray-200 rounded" @click="open = false">Close</button>
    </div>
</div>
