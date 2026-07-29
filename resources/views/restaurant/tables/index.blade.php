@extends('layouts.app')

@section('title', 'Restaurant Tables')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Restaurant Tables</h1>
            <div class="flex gap-3">
                <a href="{{ route('restaurant.tables.layout') }}" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold">
                    <i class="fa-solid fa-grid-2"></i> Layout View
                </a>
                <a href="{{ route('restaurant.tables.create') }}" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold">
                    <i class="fa-solid fa-plus"></i> Add Table
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Table Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">QR Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tables as $table)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ $table->id }}</td>
                            <td class="px-6 py-4 text-sm font-bold">{{ $table->number }}</td>
                            <td class="px-6 py-4 text-sm">{{ $table->capacity }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    {{ $table->status == 'available' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $table->status == 'occupied' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $table->status == 'reserved' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $table->status == 'unavailable' ? 'bg-gray-100 text-gray-700' : '' }}">
                                    {{ ucfirst($table->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($table->qr_code)
                                    <span class="text-green-600"><i class="fa-solid fa-check-circle"></i> Generated</span>
                                @else
                                    <span class="text-gray-400">Not generated</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('restaurant.tables.edit', $table) }}" class="text-blue-600 hover:text-blue-800 mr-2">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                                <button onclick="toggleStatus({{ $table->id }})" class="text-orange-600 hover:text-orange-800 mr-2">
                                    <i class="fa-solid fa-arrows-rotate"></i>
                                </button>
                                <button onclick="generateQR({{ $table->id }})" class="text-purple-600 hover:text-purple-800 mr-2">
                                    <i class="fa-solid fa-qrcode"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No tables found. Create one!</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $tables->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function toggleStatus(id) {
        if (confirm('Change table status?')) {
            // You can implement via AJAX or redirect to a toggle route
            alert('Please use Layout view to toggle status.');
        }
    }

    function generateQR(id) {
        if (confirm('Generate QR code for this table?')) {
            // Implement via AJAX or form submission
            alert('QR generation route is ready.');
        }
    }
</script>
@endsection 
