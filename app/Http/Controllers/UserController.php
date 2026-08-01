<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['organization', 'roles'])->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $organizations = Organization::all();
        $roles = Role::where('name', '!=', 'Super Admin')->get();
        return view('admin.users.create', compact('organizations', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'organization_id' => 'required|exists:organizations,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'organization_id' => $request->organization_id,
            'branch_id' => $request->branch_id ?? null,
        ]);

        $user->assignRole($request->role);
        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' created.");
    }

    public function show($id)
    {
        $user = User::with(['organization', 'branches', 'roles'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $organizations = Organization::all();
        $roles = Role::where('name', '!=', 'Super Admin')->get();
        return view('admin.users.edit', compact('user', 'organizations', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'organization_id' => 'required|exists:organizations,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user->update($request->only(['name', 'email', 'organization_id', 'branch_id']));

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);
        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' updated.");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->hasRole('Super Admin')) {
            return redirect()->back()->with('error', 'Cannot delete Super Admin.');
        }
        $name = $user->name;
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', "User '{$name}' deleted.");
    }
}