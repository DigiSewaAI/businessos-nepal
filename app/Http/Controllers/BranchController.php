<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Auth::user()->organization->branches;
        return view('branches.index', compact('branches'));
    }
    
    public function create()
    {
        return view('branches.create');
    }
    
    public function store(Request $request)
    {
        $organization = Auth::user()->organization;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $organization->branches()->create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'is_default' => false,
            'created_by' => Auth::id(),
        ]);
        
        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }
}