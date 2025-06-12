<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        $authUser = \Illuminate\Support\Facades\Auth::user();
        if ($authUser) {
            $authUser->following()->attach($user->id);
        }
        return back();
    }

    public function unfollow(User $user)
    {
        $authUser = auth()->user();
        if ($authUser) {
            $authUser->following()->detach($user->id);
        }
        return back();
    }
}
