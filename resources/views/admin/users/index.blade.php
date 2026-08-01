@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg shadow-cyan-200">
                        <i class="fa-solid fa-users text-white text-sm"></i>
                    </span>
                    Users
                </h1>
                <p class="text-sm text-gray-500 mt-1">Manage all platform users</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-cyan-200 transition-all duration-200 hover:shadow-xl hover:scale-[1.02]">
                <i class="fa-solid fa-user-plus"></i>
                Add User
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total</p>
                <p class="text-xl font-bold text-gray-900">{{ $users->total() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Active</p>
                <p class="text-xl font-bold text-emerald-600">{{ $users->where('status', 'active')->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Super Admins</p>
                <p class="text-xl font-bold text-purple-600">{{ $users->filter(fn($u) => $u->hasRole('Super Admin'))->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Organizations</p>
                <p class="text-xl font-bold text-indigo-600">{{ $users->pluck('organization_id')->unique()->count() }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100/80 border-b border-gray-200">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Organization</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-cyan-50/30 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-500">#{{ $user->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-100 to-cyan-200 flex items-center justify-center text-cyan-700 font-semibold text-xs">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-800">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->organization->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                    {{ $user->roles->first()->name ?? 'No Role' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $user->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->status === 'active' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    {{ ucfirst($user->status ?? 'Active') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="w-8 h-8 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 flex items-center justify-center transition">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </a>
                                    @if(!$user->hasRole('Super Admin'))
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition" onclick="return confirm('Delete this user?')">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fa-solid fa-users text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-sm text-gray-500 font-medium">No users found</p>
                                    <a href="{{ route('admin.users.create') }}" class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-medium rounded-lg transition">
                                        <i class="fa-solid fa-user-plus"></i> Add User
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection