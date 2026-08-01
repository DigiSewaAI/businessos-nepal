@extends('layouts.admin')

@section('title', 'Trial Balance')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Trial Balance</h1>
            <div class="text-sm text-gray-500">As of {{ now()->format('d M Y') }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account Name</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Debit</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Credit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $totalDebit = 0; $totalCredit = 0;
                    @endphp
                    @forelse($trialBalance as $item)
                        @php
                            $totalDebit += $item['debit'];
                            $totalCredit += $item['credit'];
                        @endphp
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono">{{ $item['code'] }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item['name'] }}</td>
                            <td class="px-6 py-4 text-sm text-right text-blue-600">{{ number_format($item['debit'], 2) }}</td>
                            <td class="px-6 py-4 text-sm text-right text-green-600">{{ number_format($item['credit'], 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400">No data found.</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50 border-t border-gray-200 font-bold">
                    <tr>
                        <td colspan="2" class="px-6 py-3 text-right text-gray-700">Total</td>
                        <td class="px-6 py-3 text-right text-blue-700">{{ number_format($totalDebit, 2) }}</td>
                        <td class="px-6 py-3 text-right text-green-700">{{ number_format($totalCredit, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
