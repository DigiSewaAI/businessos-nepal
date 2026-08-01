<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    /**
     * Display a listing of organizations (Super Admin)
     */
    public function index()
    {
        $organizations = Organization::paginate(20);
        return view('admin.organizations.index', compact('organizations'));
    }

    /**
     * Show form to create new organization (Super Admin)
     */
    public function create()
    {
        return view('admin.organizations.create');
    }

    /**
     * Store a newly created organization (Super Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Organization::create($request->only(['name', 'address', 'phone', 'email']));
        return redirect()->route('admin.organizations.index')->with('success', 'Organization created.');
    }

    /**
     * Show single organization (Super Admin)
     */
    public function show($id)
    {
        $organization = Organization::findOrFail($id);
        return view('admin.organizations.show', compact('organization'));
    }

    /**
     * Show edit form – compatible for both normal user & Super Admin
     * - $id optional: if not provided, uses own organization
     */
    public function edit($id = null)
    {
        if ($id) {
            $organization = Organization::findOrFail($id);
        } else {
            $organization = Auth::user()->organization;
        }
        return view('organizations.edit', compact('organization'));
    }

    /**
     * Update organization – compatible for both normal user & Super Admin
     */
    public function update(Request $request, $id = null)
    {
        if ($id) {
            $organization = Organization::findOrFail($id);
        } else {
            $organization = Auth::user()->organization;
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $organization->update($request->only(['name', 'address', 'phone', 'email']));
        return redirect()->back()->with('success', 'Organization updated successfully.');
    }

    /**
     * Delete organization (Super Admin)
     */
    public function destroy($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();
        return redirect()->route('admin.organizations.index')->with('success', 'Organization deleted.');
    }
}