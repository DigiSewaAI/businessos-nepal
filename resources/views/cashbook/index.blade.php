@extends('layouts.admin')

@section('title', 'Cashbook - BusinessOS Nepal')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Cashbook</h1>

        <!-- Today's Cashbook -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-bold text-lg">{{ date('d M, Y') }}</h2>
                <span class="px-3 py-1 rounded-full text-sm font-semibold 
                    @if($cashbook->status == 'open') bg-green-100 text-green-700
                    @else bg-gray-100 text-gray-700 @endif">
                    {{ ucfirst($cashbook->status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-50 p-4 rounded-xl text-center">
                    <p class="text-xs text-gray-500">Opening Balance</p>
                    <p class="text-lg font-bold text-gray-800">Rs. {{ number_format($cashbook->opening_balance, 2) }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-xl text-center">
                    <p class="text-xs text-gray-500">Cash In (Sales)</p>
                    <p class="text-lg font-bold text-green-600">Rs. {{ number_format($cashbook->total_cash_in, 2) }}</p>
                </div>
                <div class="bg-red-50 p-4 rounded-xl text-center">
                    <p class="text-xs text-gray-500">Cash Out (Expenses)</p>
                    <p class="text-lg font-bold text-red-600">Rs. {{ number_format($cashbook->total_cash_out, 2) }}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-xl text-center">
                    <p class="text-xs text-gray-500">Closing Balance</p>
                    <p class="text-lg font-bold text-blue-600">Rs. {{ number_format($cashbook->closing_balance, 2) }}</p>
                </div>
            </div>

            @if($cashbook->status == 'open')
            <div class="mt-6 border-t border-gray-200 pt-4">
                <form action="{{ route('cashbook.close') }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Physical Cash Count</label>
                        <input type="number" name="physical_count" step="0.01" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Enter actual cash">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                        <input type="text" name="notes" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Optional notes">
                    </div>
                    <button type="submit" class="gradient-bg text-white px-6 py-2 rounded-lg font-semibold hover:shadow-lg transition-all whitespace-nowrap">
                        <i class="fa-solid fa-lock"></i> Close Day
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- History -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Recent History</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200">
                        <tr>
                            <th class="text-left py-2 text-gray-500">Date</th>
                            <th class="text-right py-2 text-gray-500">Opening</th>
                            <th class="text-right py-2 text-gray-500">Cash In</th>
                            <th class="text-right py-2 text-gray-500">Cash Out</th>
                            <th class="text-right py-2 text-gray-500">Closing</th>
                            <th class="text-right py-2 text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($history as $entry)
                        <tr>
                            <td class="py-2">{{ $entry->date }}</td>
                            <td class="py-2 text-right">Rs. {{ number_format($entry->opening_balance, 2) }}</td>
                            <td class="py-2 text-right text-green-600">Rs. {{ number_format($entry->total_cash_in, 2) }}</td>
                            <td class="py-2 text-right text-red-600">Rs. {{ number_format($entry->total_cash_out, 2) }}</td>
                            <td class="py-2 text-right font-semibold">Rs. {{ number_format($entry->closing_balance, 2) }}</td>
                            <td class="py-2 text-right">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @if($entry->status == 'closed') bg-gray-100 text-gray-700
                                    @else bg-green-100 text-green-700 @endif">
                                    {{ ucfirst($entry->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
