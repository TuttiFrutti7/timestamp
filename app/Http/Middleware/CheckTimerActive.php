<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckTimerActive
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $timer = $user->timer;


        if (!$timer) {
            return redirect()->route('timer.set.view')->with('error', 'Please set your daily timer.');
        }

        $today = now()->startOfDay();
        $logs = $user->usageLogs()->where('login_time', '>=', $today)->get();

        $total = 0;
        foreach ($logs as $log) {
            if ($log->logout_time) {
                $total += $log->login_time->diffInSeconds($log->logout_time) / 60;
            } else {
                $total += $log->login_time->diffInSeconds(now()) / 60;
            }
        }

        // Neļauj lietotājam pārsniegt beigušos laika limitu
        if ($total >= $timer->limit) {
            $openLog = $user->usageLogs()
                ->where('login_time', '>=', $today)
                ->whereNull('logout_time')
                ->first();
            if ($openLog) {
                $openLog->logout_time = now();
                $openLog->save();
            }
            return redirect()->route('timer.expired')->with('error', 'You have exceeded your daily usage limit.');
            //return redirect()->route('/')->with('error', 'You have exceeded your daily usage limit.');
        }

        return $next($request);
    }
}