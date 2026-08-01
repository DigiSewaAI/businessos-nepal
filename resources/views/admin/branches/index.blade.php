@extends('layouts.admin')

@section('title', 'Branches')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg shadow-cyan-200">
                    <i class="fa-solid fa-store text-white text-sm"></i>
                </span>
                Branches
            </h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100/80 border-b border-gray-200">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Branch</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Organization</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($branches as $branch)
                        <tr class="hover:bg-cyan-50/30 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-500">#{{ $branch->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-100 to-cyan-200 flex items-center justify-center text-cyan-700 font-semibold text-xs">
                                        {{ substr($branch->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-800">{{ $branch->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $branch->organization->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $branch->address ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ ($branch->is_active ?? true) ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ ($branch->is_active ?? true) ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    {{ ($branch->is_active ?? true) ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fa-solid fa-store text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-sm text-gray-500 font-medium">No branches found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $branches->links() }}
        </div>
    </div>
</div>
@endsection