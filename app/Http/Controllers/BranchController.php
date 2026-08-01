<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ✅ Super Admin ले सबै organizations का branches हेर्ने
        if ($user->hasRole('Super Admin')) {
            $branches = Branch::with('organization')->paginate(20);
            return view('admin.branches.index', compact('branches'));
        }

        // Normal user ले आफ्नो organization का branches हेर्ने
        $branches = $user->organization->branches;
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

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
        ]);

        $branch->update($request->only(['name', 'address', 'phone']));
        return redirect()->route('branches.index')->with('success', 'Branch updated.');
    }

    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted.');
    }
}