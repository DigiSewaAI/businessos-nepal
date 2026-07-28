<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class CheckPlanLimits
{
    public function handle(Request $request, Closure $next, $resource = null)
    {
        $user = Auth::user();
        if (!$user) {
            return $next($request);
        }

        $org = $user->organization;
        if (!$org) {
            return $next($request);
        }

        $subscription = $org->subscription;
        if (!$subscription) {
            // If no subscription, assign Free plan
            $freePlan = Plan::where('slug', 'free')->first();
            if ($freePlan) {
                $subscription = $org->subscriptions()->create([
                    'plan_id' => $freePlan->id,
                    'starts_at' => now(),
                    'is_active' => true,
                ]);
            } else {
                // Fallback: allow
                return $next($request);
            }
        }

        $plan = $subscription->plan;
        $features = $plan->features ?? [];

        // If the middleware is called with a resource parameter, check limits
        if ($resource) {
            $limitKey = 'max_' . $resource; // e.g., max_products, max_users, max_branches
            $limit = $features[$limitKey] ?? null;

            if ($limit !== null && $limit >= 0) {
                // Count current usage
                $count = $org->{$resource}()->count(); // assumes relationship exists
                if ($count >= $limit) {
                    abort(403, "You have reached the maximum allowed {$resource} ({$limit}). Please upgrade your plan.");
                }
            }
        }

        return $next($request);
    }
}