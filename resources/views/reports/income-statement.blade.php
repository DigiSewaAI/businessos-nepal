@extends('layouts.app')

@section('title', 'Income Statement')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Income Statement</h1>
            <div class="text-sm text-gray-500">{{ $start_date }} to {{ $end_date }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-6">
            <!-- Revenue -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Revenue</h3>
                <table class="w-full mt-2">
                    @foreach($revenue as $item)
                        <tr>
                            <td class="py-2 text-sm">{{ $item['name'] }}</td>
                            <td class="py-2 text-sm text-right">{{ number_format($item['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="font-bold border-t border-gray-200">
                        <td class="py-2 text-sm">Total Revenue</td>
                        <td class="py-2 text-sm text-right text-blue-600">{{ number_format($total_revenue, 2) }}</td>
                    </tr>
                </table>
            </div>

            <!-- Expenses -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Expenses</h3>
                <table class="w-full mt-2">
                    @foreach($expenses as $item)
                        <tr>
                            <td class="py-2 text-sm">{{ $item['name'] }}</td>
                            <td class="py-2 text-sm text-right">{{ number_format($item['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="font-bold border-t border-gray-200">
                        <td class="py-2 text-sm">Total Expenses</td>
                        <td class="py-2 text-sm text-right text-red-600">{{ number_format($total_expenses, 2) }}</td>
                    </tr>
                </table>
            </div>

            <!-- Net Income -->
            <div class="border-t-2 border-gray-300 pt-4 mt-4">
                <div class="flex justify-between items-center">
                    <h4 class="text-xl font-bold text-gray-900">Net Income</h4>
                    <span class="text-2xl font-bold {{ $net_income >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($net_income, 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection