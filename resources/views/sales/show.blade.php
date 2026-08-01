@extends('layouts.admin')

@section('title', 'Sale #' . $sale->invoice_no)

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Invoice #{{ $sale->invoice_no }}</h1>
                    <p class="text-sm text-gray-500">{{ $sale->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-semibold 
                    @if($sale->status == 'completed') bg-green-100 text-green-700
                    @elseif($sale->status == 'cancelled') bg-red-100 text-red-700
                    @else bg-yellow-100 text-yellow-700 @endif">
                    {{ ucfirst($sale->status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-xl mb-6">
                <div>
                    <p class="text-xs text-gray-500">Customer</p>
                    <p class="font-semibold">{{ $sale->customer->name ?? 'Walk-in Customer' }}</p>
                    <p class="text-sm text-gray-500">{{ $sale->customer->phone ?? '' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Cashier</p>
                    <p class="font-semibold">{{ $sale->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Payment Method</p>
                    <p class="font-semibold">{{ ucfirst($sale->payment_method) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Payment Status</p>
                    <p class="font-semibold">{{ ucfirst($sale->payment_status) }}</p>
                </div>
            </div>

            <table class="w-full mb-6">
                <thead class="border-b border-gray-200">
                    <tr>
                        <th class="py-2 text-left text-sm font-semibold text-gray-600">Product</th>
                        <th class="py-2 text-right text-sm font-semibold text-gray-600">Qty</th>
                        <th class="py-2 text-right text-sm font-semibold text-gray-600">Price</th>
                        <th class="py-2 text-right text-sm font-semibold text-gray-600">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($sale->lines as $line)
                    <tr>
                        <td class="py-2 text-sm text-gray-800">{{ $line->product->name }} @if($line->variant) ({{ $line->variant->name }}) @endif</td>
                        <td class="py-2 text-sm text-right">{{ $line->quantity }}</td>
                        <td class="py-2 text-sm text-right">Rs. {{ number_format($line->price, 2) }}</td>
                        <td class="py-2 text-sm font-semibold text-right">Rs. {{ number_format($line->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-gray-200">
                    <tr>
                        <td colspan="3" class="py-2 text-right font-medium">Subtotal</td>
                        <td class="py-2 text-right font-medium">Rs. {{ number_format($sale->subtotal, 2) }}</td>
                    </tr>
                    @if($sale->discount > 0)
                    <tr>
                        <td colspan="3" class="py-2 text-right text-green-600">Discount</td>
                        <td class="py-2 text-right text-green-600">- Rs. {{ number_format($sale->discount, 2) }}</td>
                    </tr>
                    @endif
                    @if($sale->tax > 0)
                    <tr>
                        <td colspan="3" class="py-2 text-right text-gray-600">Tax</td>
                        <td class="py-2 text-right text-gray-600">+ Rs. {{ number_format($sale->tax, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="border-t border-gray-200">
                        <td colspan="3" class="py-2 text-right font-bold text-lg">Total</td>
                        <td class="py-2 text-right font-bold text-lg text-blue-600">Rs. {{ number_format($sale->total, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="py-2 text-right text-sm text-gray-500">Paid</td>
                        <td class="py-2 text-right text-sm text-gray-500">Rs. {{ number_format($sale->paid_amount, 2) }}</td>
                    </tr>
                    @if($sale->due_amount > 0)
                    <tr>
                        <td colspan="3" class="py-2 text-right text-sm text-red-600">Due</td>
                        <td class="py-2 text-right text-sm text-red-600">Rs. {{ number_format($sale->due_amount, 2) }}</td>
                    </tr>
                    @endif
                </tfoot>
            </table>
            <a href="{{ route('sales.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Sales
            </a>
        </div>
    </div>
</div>
@endsection
