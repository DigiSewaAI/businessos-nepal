@extends('layouts.admin')

@section('title', 'Subscription Details')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.subscriptions.index') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-200">
                            <i class="fa-solid fa-credit-card text-white text-sm"></i>
                        </span>
                        Subscription #{{ $subscription->id }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $subscription->organization->name ?? 'Unknown Organization' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" method="POST" style="display:inline-block">
                    @csrf @method('DELETE')
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition" onclick="return confirm('Delete this subscription?')">
                        <i class="fa-solid fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                    <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-circle-info text-blue-500 mr-2"></i> Subscription Details</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">ID</span>
                        <span class="text-sm font-medium text-gray-800">#{{ $subscription->id }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">Organization</span>
                        <span class="text-sm font-medium text-gray-800">{{ $subscription->organization->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">Plan</span>
                        <span class="text-sm font-medium text-gray-800">{{ $subscription->plan->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">Price</span>
                        <span class="text-sm font-bold text-gray-900">Rs. {{ number_format($subscription->price ?? $subscription->plan->price ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">Start Date</span>
                        <span class="text-sm font-medium text-gray-800">{{ $subscription->start_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-sm text-gray-500">End Date</span>
                        <span class="text-sm font-medium text-gray-800">{{ $subscription->end_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Status</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium 
                            {{ $subscription->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 
                               ($subscription->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                               ($subscription->status === 'expired' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600')) }}">
                            <span class="w-1.5 h-1.5 rounded-full 
                                {{ $subscription->status === 'active' ? 'bg-emerald-500' : 
                                   ($subscription->status === 'pending' ? 'bg-yellow-500' : 
                                   ($subscription->status === 'expired' ? 'bg-red-500' : 'bg-gray-400')) }}"></span>
                            {{ ucfirst($subscription->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100/50">
                    <h3 class="font-semibold text-gray-800"><i class="fa-solid fa-money-bill-wave text-green-500 mr-2"></i> Payment History</h3>
                </div>
                <div class="p-6">
                    @if($subscription->payments->count())
                        <div class="space-y-2">
                            @foreach($subscription->payments->take(5) as $payment)
                                <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                                    <div>
                                        <span class="text-sm text-gray-700">#{{ $payment->id }}</span>
                                        <span class="text-sm font-medium text-gray-800 ml-2">Rs. {{ number_format($payment->amount, 2) }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</span>
                                </div>
                            @endforeach
                            @if($subscription->payments->count() > 5)
                                <p class="text-xs text-gray-400 text-center pt-2">+ {{ $subscription->payments->count() - 5 }} more payments</p>
                            @endif
                        </div>
                    @else
                        <p class="text-sm text-gray-400 text-center py-2">No payments recorded</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection