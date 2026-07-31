<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // ✅ Validation with industry and business_category
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'org_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'terms' => ['required'],
            // ✅ NEW: Phase A Industry fields validation
            'industry' => ['required', 'string', 'in:' . implode(',', array_keys(config('businessos.industries')))],
            'business_category' => ['nullable', 'string'],
        ]);

        // 1. Create Organization (with industry & business_category)
        $organization = Organization::create([
            'name' => $request->org_name,
            'slug' => Str::slug($request->org_name) . '-' . Str::random(5),
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 'active',
            'plan_id' => 1,
            'currency' => 'NPR',
            'created_by' => null,
            // ✅ NEW: store industry and business_category
            'industry' => $request->industry ?? 'retail', // fallback if somehow missing
            'business_category' => $request->business_category ?? null,
        ]);

        // 2. Create Main Branch
        $branch = Branch::create([
            'organization_id' => $organization->id,
            'name' => 'Main Branch',
            'code' => 'MB-' . Str::random(4),
            'is_main' => true,
            'status' => 'active',
            'created_by' => null,
        ]);

        // 3. Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'organization_id' => $organization->id,
            'branch_id' => $branch->id,
            'phone' => $request->phone,
            'is_active' => true,
            'onboarding_completed' => false,
        ]);

        // 4. Assign 'Owner' Role (Spatie)
        $role = \Spatie\Permission\Models\Role::where('name', 'Owner')->first();
        if ($role) {
            $user->assignRole('Owner');
        } else {
            $role = \Spatie\Permission\Models\Role::create(['name' => 'Owner', 'guard_name' => 'web']);
            $user->assignRole('Owner');
        }

        // 5. Update organization and branch with created_by
        $organization->update(['created_by' => $user->id]);
        $branch->update(['created_by' => $user->id]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect to onboarding
        return redirect(route('onboarding'));
    }
}