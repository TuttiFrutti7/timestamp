<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Timer;

class TimerController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

    public function showSetForm()
    {
        $user = Auth::user();
        $timer = $user->timer;

        if ($timer && !$user->canChangeTimer()) {
            //return redirect()->route('timer.expired')->with('error', 'You can only change your timer once every 7 days.');
            //return redirect()->route('posts.index');
            return redirect()->back();
        }

        return view('timer.set');
    }

    public function set(Request $request)
    {
        $request->validate([
            'limit' => 'required|integer|min:1|max:60',
        ]);

        $user = Auth::user();
        $timer = $user->timer;

        if ($timer && !$user->canChangeTimer()) {
            //return back()->with('error', 'You can only change your timer once every 7 days.');
            //return redirect()->route('posts.index');
            return redirect()->back();
        }

        Timer::updateOrCreate(
            ['user_id' => $user->id],
            ['limit' => $request->limit, 'set_at' => now()]
        );

        // Izveido jaunu UsageLog ierakstu jaunai dienai
        $today = now()->startOfDay();
        $openLog = $user->usageLogs()->whereNull('logout_time')->orderBy('login_time', 'desc')->first();

        if ($openLog && $openLog->login_time < $today) {
            // Aizver iepriekšējās dienas logu pie pusnakts
            $openLog->logout_time = $today; // aizver tieši pusnaktī
            $openLog->save();

            // Izveido jaunu logu šodienai
            \App\Models\UsageLog::create([
                'user_id' => $user->id,
                'login_time' => $today,
            ]);
        }

        //return back()->with('success', 'Timer set successfully!');
        return redirect()->route('posts.index')->with('success', 'Timer set successfully!');
    }

    public function expired()
    {
        return view('timer.expired');
    }

}
