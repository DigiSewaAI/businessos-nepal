@extends('layouts.admin')

@section('title', 'Purchase #' . $purchase->po_no)

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Purchase #{{ $purchase->po_no }}</h1>
                    <p class="text-sm text-gray-500">{{ $purchase->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-semibold 
                    @if($purchase->status == 'received') bg-green-100 text-green-700
                    @elseif($purchase->status == 'ordered') bg-blue-100 text-blue-700
                    @elseif($purchase->status == 'draft') bg-gray-100 text-gray-700
                    @elseif($purchase->status == 'cancelled') bg-red-100 text-red-700
                    @else bg-yellow-100 text-yellow-700 @endif">
                    {{ ucfirst($purchase->status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-xl mb-6">
                <div>
                    <p class="text-xs text-gray-500">Supplier</p>
                    <p class="font-semibold">{{ $purchase->supplier->name }}</p>
                    <p class="text-sm text-gray-500">{{ $purchase->supplier->phone }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Created By</p>
                    <p class="font-semibold">{{ $purchase->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Supplier Invoice</p>
                    <p class="font-semibold">{{ $purchase->invoice_no ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Expected Date</p>
                    <p class="font-semibold">{{ $purchase->expected_date ? date('d M Y', strtotime($purchase->expected_date)) : 'N/A' }}</p>
                </div>
            </div>

            <table class="w-full mb-6">
                <thead class="border-b border-gray-200">
                    <tr>
                        <th class="py-2 text-left text-sm font-semibold text-gray-600">Product</th>
                        <th class="py-2 text-right text-sm font-semibold text-gray-600">Qty</th>
                        <th class="py-2 text-right text-sm font-semibold text-gray-600">Received</th>
                        <th class="py-2 text-right text-sm font-semibold text-gray-600">Price</th>
                        <th class="py-2 text-right text-sm font-semibold text-gray-600">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($purchase->lines as $line)
                    <tr>
                        <td class="py-2 text-sm text-gray-800">{{ $line->product->name }} @if($line->variant) ({{ $line->variant->name }}) @endif</td>
                        <td class="py-2 text-sm text-right">{{ $line->quantity }}</td>
                        <td class="py-2 text-sm text-right text-green-600">{{ $line->received_quantity }}</td>
                        <td class="py-2 text-sm text-right">Rs. {{ number_format($line->purchase_price, 2) }}</td>
                        <td class="py-2 text-sm font-semibold text-right">Rs. {{ number_format($line->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-gray-200">
                    <tr>
                        <td colspan="4" class="py-2 text-right font-medium">Subtotal</td>
                        <td class="py-2 text-right font-medium">Rs. {{ number_format($purchase->subtotal, 2) }}</td>
                    </tr>
                    @if($purchase->discount > 0)
                    <tr>
                        <td colspan="4" class="py-2 text-right text-green-600">Discount</td>
                        <td class="py-2 text-right text-green-600">- Rs. {{ number_format($purchase->discount, 2) }}</td>
                    </tr>
                    @endif
                    @if($purchase->tax > 0)
                    <tr>
                        <td colspan="4" class="py-2 text-right text-gray-600">Tax</td>
                        <td class="py-2 text-right text-gray-600">+ Rs. {{ number_format($purchase->tax, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="border-t border-gray-200">
                        <td colspan="4" class="py-2 text-right font-bold text-lg">Total</td>
                        <td class="py-2 text-right font-bold text-lg text-blue-600">Rs. {{ number_format($purchase->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="flex gap-3">
                <a href="{{ route('purchases.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    <i class="fa-solid fa-arrow-left"></i> Back to Purchases
                </a>
                @if($purchase->status == 'ordered')
                <form action="{{ route('purchases.receive', $purchase) }}" method="POST" class="inline">
                    @csrf
                    @foreach($purchase->lines as $line)
                    <input type="hidden" name="items[{{ $loop->index }}][line_id]" value="{{ $line->id }}">
                    <input type="hidden" name="items[{{ $loop->index }}][quantity]" value="{{ $line->quantity - $line->received_quantity }}">
                    @endforeach
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition">
                        <i class="fa-solid fa-check"></i> Receive All Stock
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
