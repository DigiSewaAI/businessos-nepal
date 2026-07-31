<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOnboarding
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // If user hasn't completed onboarding, redirect to onboarding
        if ($user && !$user->onboarding_completed) {
            return redirect()->route('onboarding');
        }
        
        return $next($request);
    }
}