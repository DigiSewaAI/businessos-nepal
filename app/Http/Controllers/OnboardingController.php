<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $organization = $user->organization;
        
        return view('onboarding.index', compact('user', 'organization'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'business_type' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'currency' => 'required|string|max:10',
            'language' => 'required|string|max:10',
        ]);
        
        $user = Auth::user();
        $organization = $user->organization;
        
        // Update organization
        $organization->update([
            'business_type' => $request->business_type,
            'address' => $request->address,
            'currency' => $request->currency,
            'language' => $request->language,
        ]);
        
        // Mark onboarding as complete
        $user->update([
            'onboarding_completed' => true,
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Welcome to BusinessOS Nepal!');
    }
}