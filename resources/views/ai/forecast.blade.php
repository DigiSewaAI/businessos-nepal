@extends('layouts.admin')

@section('title', 'AI Forecasts')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">📈 AI Forecasts</h1>
            <button onclick="showForecastModal()" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="fa-solid fa-plus"></i> Generate Forecast
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">All Forecasts</h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metric</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Predictions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Confidence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Forecast Until</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($forecasts as $forecast)
                        <tr>
                            <td class="px-6 py-4 text-sm capitalize">{{ $forecast->metric }}</td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $preds = $forecast->predictions ?? [];
                                    $last = end($preds);
                                @endphp
                                @if($preds)
                                    {{ count($preds) }} days
                                    <span class="text-xs text-gray-400 block">Latest: Rs. {{ number_format($last['value'] ?? 0, 2) }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $forecast->confidence ?? 0 }}%</td>
                            <td class="px-6 py-4 text-sm">{{ $forecast->forecast_until?->format('d M Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $forecast->created_at->format('d M Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">No forecasts yet. Generate one!</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $forecasts->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="forecastModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Generate Forecast</h3>
        <form action="{{ route('ai.forecast.generate') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Metric</label>
                <select name="metric" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    <option value="sales">Sales</option>
                    <option value="purchases">Purchases</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Days to Forecast</label>
                <input type="number" name="days" value="30" min="7" max="365" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeForecastModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="gradient-bg text-white px-4 py-2 rounded-lg font-semibold">Generate</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showForecastModal() {
        document.getElementById('forecastModal').classList.remove('hidden');
        document.getElementById('forecastModal').classList.add('flex');
    }
    function closeForecastModal() {
        document.getElementById('forecastModal').classList.add('hidden');
        document.getElementById('forecastModal').classList.remove('flex');
    }
</script>
@endsection 
