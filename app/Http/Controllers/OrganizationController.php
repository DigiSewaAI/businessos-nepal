<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function edit()
    {
        $organization = Auth::user()->organization;
        return view('organizations.edit', compact('organization'));
    }
    
    public function update(Request $request)
    {
        $organization = Auth::user()->organization;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);
        
        $organization->update($request->only(['name', 'address', 'phone', 'email']));
        
        return redirect()->back()->with('success', 'Organization updated successfully.');
    }
}