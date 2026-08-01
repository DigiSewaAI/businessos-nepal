@extends('layouts.admin')

@section('title', 'Payment Details')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.payments.index') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-lg shadow-emerald-200">
                            <i class="fa-solid fa-receipt text-white text-sm"></i>
                        </span>
                        Payment #{{ $payment->id }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $payment->organization->name ?? 'Unknown Organization' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.payments.edit', $payment->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" style="display:inline-block">
                    @csrf @method('DELETE')
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition" onclick="return confirm('Delete this payment?')">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-circle-info text-emerald-500 mr-2"></i> Payment Information</h3>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-sm text-gray-500">ID</span>
                    <span class="text-sm font-medium text-gray-800">#{{ $payment->id }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-sm text-gray-500">Organization</span>
                    <span class="text-sm font-medium text-gray-800">{{ $payment->organization->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-sm text-gray-500">Amount</span>
                    <span class="text-sm font-bold text-gray-900 text-lg">Rs. {{ number_format($payment->amount, 2) }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-sm text-gray-500">Payment Method</span>
                    <span class="text-sm font-medium text-gray-800">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-sm text-gray-500">Transaction ID</span>
                    <span class="text-sm font-medium text-gray-800">{{ $payment->transaction_id ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-sm text-gray-500">Status</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium 
                        {{ $payment->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 
                           ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                           ($payment->status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600')) }}">
                        <span class="w-1.5 h-1.5 rounded-full 
                            {{ $payment->status === 'completed' ? 'bg-emerald-500' : 
                               ($payment->status === 'pending' ? 'bg-yellow-500' : 
                               ($payment->status === 'failed' ? 'bg-red-500' : 'bg-gray-400')) }}"></span>
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Payment Date</span>
                    <span class="text-sm font-medium text-gray-800">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y H:i') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection