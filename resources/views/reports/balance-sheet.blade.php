@extends('layouts.admin')

@section('title', 'Balance Sheet')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Balance Sheet</h1>
            <div class="text-sm text-gray-500">As of {{ $as_of_date }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-6">
            <!-- Assets -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Assets</h3>
                <table class="w-full mt-2">
                    @foreach($assets as $item)
                        <tr>
                            <td class="py-2 text-sm">{{ $item['name'] }}</td>
                            <td class="py-2 text-sm text-right">{{ number_format($item['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="font-bold border-t border-gray-200">
                        <td class="py-2 text-sm">Total Assets</td>
                        <td class="py-2 text-sm text-right text-blue-600">{{ number_format($total_assets, 2) }}</td>
                    </tr>
                </table>
            </div>

            <!-- Liabilities -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Liabilities</h3>
                <table class="w-full mt-2">
                    @foreach($liabilities as $item)
                        <tr>
                            <td class="py-2 text-sm">{{ $item['name'] }}</td>
                            <td class="py-2 text-sm text-right">{{ number_format($item['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="font-bold border-t border-gray-200">
                        <td class="py-2 text-sm">Total Liabilities</td>
                        <td class="py-2 text-sm text-right text-orange-600">{{ number_format($total_liabilities, 2) }}</td>
                    </tr>
                </table>
            </div>

            <!-- Equity -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Equity</h3>
                <table class="w-full mt-2">
                    @foreach($equity as $item)
                        <tr>
                            <td class="py-2 text-sm">{{ $item['name'] }}</td>
                            <td class="py-2 text-sm text-right">{{ number_format($item['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="font-bold border-t border-gray-200">
                        <td class="py-2 text-sm">Total Equity</td>
                        <td class="py-2 text-sm text-right text-green-600">{{ number_format($total_equity, 2) }}</td>
                    </tr>
                </table>
            </div>

            <!-- Check -->
            <div class="border-t-2 border-gray-300 pt-4 mt-4">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="text-gray-600">Total Assets</div>
                    <div class="text-right font-bold text-blue-600">{{ number_format($total_assets, 2) }}</div>
                    <div class="text-gray-600">Total Liabilities + Equity</div>
                    <div class="text-right font-bold text-green-600">{{ number_format($total_liabilities + $total_equity, 2) }}</div>
                </div>
                @if(round($total_assets, 2) == round($total_liabilities + $total_equity, 2))
                    <p class="text-green-500 text-sm font-semibold mt-2 text-center">✅ Balance Sheet is balanced!</p>
                @else
                    <p class="text-red-500 text-sm font-semibold mt-2 text-center">⚠️ Balance Sheet is NOT balanced! Check entries.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
