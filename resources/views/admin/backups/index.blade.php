@extends('layouts.admin')

@section('title', 'Backups')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-yellow-600 flex items-center justify-center shadow-lg shadow-amber-200">
                        <i class="fa-solid fa-database text-white text-sm"></i>
                    </span>
                    Backups
                </h1>
                <p class="text-sm text-gray-500 mt-1">Manage system backups</p>
            </div>
            <form action="{{ route('admin.backups.create') }}" method="POST">
                @csrf
                <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-600 to-yellow-600 hover:from-amber-700 hover:to-yellow-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-amber-200 transition-all duration-200 hover:shadow-xl hover:scale-[1.02]">
                    <i class="fa-solid fa-plus"></i>
                    Create Backup
                </button>
            </form>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Backups</p>
                <p class="text-xl font-bold text-gray-900">{{ count($backupInfo ?? []) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Total Size</p>
                <p class="text-xl font-bold text-indigo-600">
                    @php
                        $totalSize = collect($backupInfo ?? [])->sum('size');
                        echo $totalSize > 1024 ? round($totalSize / 1024, 2) . ' MB' : $totalSize . ' KB';
                    @endphp
                </p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Latest Backup</p>
                <p class="text-xl font-bold text-gray-900">
                    @if(!empty($backupInfo))
                        {{ \Carbon\Carbon::createFromTimestamp($backupInfo[0]['modified'])->format('M d, Y') }}
                    @else
                        -
                    @endif
                </p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100/80 border-b border-gray-200">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Filename</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Size</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Modified</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($backupInfo ?? [] as $index => $backup)
                        <tr class="hover:bg-amber-50/30 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-800">{{ $backup['name'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $backup['size'] > 1024 ? round($backup['size'] / 1024, 2) . ' MB' : $backup['size'] . ' KB' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::createFromTimestamp($backup['modified'])->format('M d, Y H:i:s') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.backups.download', $backup['name']) }}" class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition">
                                        <i class="fa-solid fa-download text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.backups.destroy', $backup['name']) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition" onclick="return confirm('Delete this backup?')">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                        <i class="fa-solid fa-database text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-sm text-gray-500 font-medium">No backups found</p>
                                    <form action="{{ route('admin.backups.create') }}" method="POST">
                                        @csrf
                                        <button class="mt-2 inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition">
                                            <i class="fa-solid fa-plus"></i> Create Backup
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection