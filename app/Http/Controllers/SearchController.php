<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Community;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        $users = User::where('username', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->get();

        $communities = Community::where('name', 'like', "%{$query}%")->get();

        return view('search.index', compact('query', 'users', 'communities'));
    }
}
?>
