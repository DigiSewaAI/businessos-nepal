@extends('layouts.admin')

@section('title', 'Anomalies')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">🚨 Anomalies</h1>
            <form action="{{ route('ai.anomalies.check') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="number" name="days" value="7" min="1" max="30" class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-20">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700">
                    <i class="fa-solid fa-scan"></i> Check Now
                </button>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Detected Anomalies</h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($anomalies as $anomaly)
                        <tr>
                            <td class="px-6 py-4 text-sm capitalize">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $anomaly->data['type'] == 'sales' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                                    {{ $anomaly->data['type'] ?? 'unknown' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $anomaly->data['message'] ?? 'Anomaly detected' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $anomaly->priority == 'high' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $anomaly->priority == 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $anomaly->priority == 'low' ? 'bg-blue-100 text-blue-700' : '' }}">
                                    {{ ucfirst($anomaly->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $anomaly->created_at->format('d M Y h:i A') }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($anomaly->is_read)
                                    <span class="text-green-600">✅ Read</span>
                                @else
                                    <span class="text-yellow-600">🟡 Unread</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No anomalies detected. 🎉</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $anomalies->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 
