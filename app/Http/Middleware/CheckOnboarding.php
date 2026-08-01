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

        // ✅ Super Admin लाई skip गर्नुहोस्
        if ($user && $user->hasRole('Super Admin')) {
            return $next($request);
        }

        // ✅ Normal user को onboarding check
        if ($user && !$user->organization) {
            return redirect()->route('onboarding');
        }

        return $next($request);
    }
}