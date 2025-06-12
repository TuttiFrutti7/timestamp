<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'username' => $googleUser->getNickname() ?? $googleUser->getName(),
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                // Add more fields if needed
            ]
        );

        Auth::login($user, true);

        return redirect()->route('dashboard');
    }
}
