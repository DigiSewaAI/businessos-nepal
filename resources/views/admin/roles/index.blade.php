@extends('layouts.admin')

@section('title', 'Roles')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                        <i class="fa-solid fa-user-shield text-white text-sm"></i>
                    </span>
                    Roles & Permissions
                </h1>
                <p class="text-sm text-gray-500 mt-1">Manage system roles and permissions</p>
            </div>
            <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-indigo-200 transition-all duration-200 hover:shadow-xl hover:scale-[1.02]">
                <i class="fa-solid fa-plus"></i>
                Create Role
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total</p>
                <p class="text-xl font-bold text-gray-900">{{ $roles->total() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">System Roles</p>
                <p class="text-xl font-bold text-purple-600">{{ $roles->where('name', 'Super Admin')->count() + $roles->where('name', 'Owner')->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Custom Roles</p>
                <p class="text-xl font-bold text-indigo-600">{{ $roles->whereNotIn('name', ['Super Admin', 'Owner'])->count() }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100/80 border-b border-gray-200">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Users</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Permissions</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($roles as $role)
                        <tr class="hover:bg-indigo-50/30 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-500">#{{ $role->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center text-indigo-700 font-semibold text-xs">
                                        {{ substr($role->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-800">{{ $role->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $role->users_count ?? 0 }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    {{ $role->permissions->count() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $role->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.roles.show', $role->id) }}" class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                    @if($role->name !== 'Super Admin')
                                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="w-8 h-8 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 flex items-center justify-center transition">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition" onclick="return confirm('Delete this role?')">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fa-solid fa-user-shield text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-sm text-gray-500 font-medium">No roles found</p>
                                    <a href="{{ route('admin.roles.create') }}" class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                                        <i class="fa-solid fa-plus"></i> Create Role
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($roles->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                {{ $roles->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection