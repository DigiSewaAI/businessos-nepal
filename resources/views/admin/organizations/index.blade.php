@extends('layouts.admin')

@section('title', 'Organizations')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-200">
                        <i class="fa-solid fa-building text-white text-sm"></i>
                    </span>
                    Organizations
                </h1>
                <p class="text-sm text-gray-500 mt-1">Manage all registered organizations on the platform</p>
            </div>
            <a href="{{ route('admin.organizations.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-emerald-200 transition-all duration-200 hover:shadow-xl hover:scale-[1.02]">
                <i class="fa-solid fa-plus"></i>
                Create Organization
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total</p>
                <p class="text-xl font-bold text-gray-900">{{ $organizations->total() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Active</p>
                <p class="text-xl font-bold text-emerald-600">{{ $organizations->where('status', 'active')->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Trial</p>
                <p class="text-xl font-bold text-amber-600">{{ $organizations->where('status', 'trial')->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Users</p>
                <p class="text-xl font-bold text-indigo-600">{{ $organizations->sum('users_count') }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100/80 border-b border-gray-200">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Organization</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Users</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Plan</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($organizations as $org)
                        <tr class="hover:bg-emerald-50/30 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-500">#{{ $org->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center text-emerald-700 font-semibold text-xs">
                                        {{ substr($org->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-800">{{ $org->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $org->email ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $org->users_count ?? 0 }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    {{ $org->plan ?? 'Free' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-emerald-100 text-emerald-700',
                                        'trial' => 'bg-amber-100 text-amber-700',
                                        'inactive' => 'bg-gray-100 text-gray-700',
                                        'suspended' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$org->status ?? 'inactive'] ?? 'bg-gray-100 text-gray-600' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ ($org->status ?? 'inactive') === 'active' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    {{ ucfirst($org->status ?? 'Inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.organizations.show', $org->id) }}" class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.organizations.edit', $org->id) }}" class="w-8 h-8 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 flex items-center justify-center transition">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.organizations.destroy', $org->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition" onclick="return confirm('Delete this organization?')">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fa-solid fa-building text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-sm text-gray-500 font-medium">No organizations found</p>
                                    <p class="text-xs text-gray-400">Start by creating your first organization</p>
                                    <a href="{{ route('admin.organizations.create') }}" class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition">
                                        <i class="fa-solid fa-plus"></i> Create Organization
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($organizations->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                {{ $organizations->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection