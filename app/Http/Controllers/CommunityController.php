<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Community;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('communities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:communities',
        'description' => 'nullable|string',
        'type' => 'required|in:public,private,hidden',
    ]);

    $community = Community::create([
        'name' => $request->name,
        'description' => $request->description,
        'type' => $request->type,
        'owner_id' => $request->user()->id,
    ]);

    // Optionally, add the creator as a member
    $community->users()->attach($request->user()->id);

    return redirect()->route('communities.show', $community);
}

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Community $community)
    {
        return view('communities.show', compact('community'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
public function search(Request $request)
{
    $query = $request->input('q');
    $communities = Community::where('name', 'like', "%{$query}%")->get();

    return view('communities.search', compact('communities', 'query'));
}
public function join(Request $request, Community $community)
{
    $request->user()->communities()->syncWithoutDetaching([$community->id]);
    return back()->with('success', 'You joined the community!');
}

public function leave(Request $request, Community $community)
{
    $request->user()->communities()->detach($community->id);
    return back()->with('success', 'You left the community.');
}
}
