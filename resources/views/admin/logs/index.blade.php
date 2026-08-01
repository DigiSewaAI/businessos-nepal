@extends('layouts.admin')

@section('title', 'System Logs')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center shadow-lg shadow-gray-200">
                    <i class="fa-solid fa-file-lines text-white text-sm"></i>
                </span>
                System Logs
            </h1>
            <p class="text-sm text-gray-500 mt-1">Audit trail of all platform activities</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" placeholder="Search logs..." class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none transition">
                </div>
                <select class="px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 outline-none transition bg-white">
                    <option value="">All Actions</option>
                    @if(isset($logTypes))
                    @foreach($logTypes as $type)
                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                    @endforeach
                    @endif
                </select>
                <button class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                    <i class="fa-solid fa-sliders"></i> Filter
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100/80 border-b border-gray-200">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Model</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Organization</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($logs ?? [] as $log)
                        <tr class="hover:bg-gray-50/30 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $log->created_at->format('H:i:s') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $log->user->name ?? 'System' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ class_basename($log->model_type) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $log->organization->name ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fa-solid fa-file-lines text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-sm text-gray-500 font-medium">No logs found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($logs) && $logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                {{ $logs->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection