@extends('layouts.admin')

@section('title', 'Record Payment')

@section('content')
<div class="py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.payments.index') }}" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-lg shadow-emerald-200">
                        <i class="fa-solid fa-money-bill-wave text-white text-sm"></i>
                    </span>
                    Record Payment
                </h1>
                <p class="text-sm text-gray-500 mt-1">Record a new payment transaction</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden">
            <form action="{{ route('admin.payments.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="organization_id" class="block text-sm font-medium text-gray-700 mb-1.5">Organization <span class="text-red-500">*</span></label>
                            <select id="organization_id" name="organization_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition @error('organization_id') border-red-500 @enderror"
                                    required>
                                <option value="">Select Organization</option>
                                @foreach($organizations ?? [] as $org)
                                    <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                                @endforeach
                            </select>
                            @error('organization_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="subscription_id" class="block text-sm font-medium text-gray-700 mb-1.5">Subscription (Optional)</label>
                            <select id="subscription_id" name="subscription_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                                <option value="">None</option>
                                @foreach($subscriptions ?? [] as $sub)
                                    <option value="{{ $sub->id }}" {{ old('subscription_id') == $sub->id ? 'selected' : '' }}>#{{ $sub->id }} - {{ $sub->organization->name ?? '' }}</option>
                                @endforeach
                            </select>
                            @error('subscription_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1.5">Amount (NPR) <span class="text-red-500">*</span></label>
                            <input type="number" id="amount" name="amount" value="{{ old('amount') }}" step="0.01"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition @error('amount') border-red-500 @enderror"
                                   placeholder="0.00" required>
                            @error('amount') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1.5">Payment Method <span class="text-red-500">*</span></label>
                            <select id="payment_method" name="payment_method"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition @error('payment_method') border-red-500 @enderror"
                                    required>
                                <option value="">Select Method</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                <option value="esewa" {{ old('payment_method') == 'esewa' ? 'selected' : '' }}>eSewa</option>
                                <option value="khalti" {{ old('payment_method') == 'khalti' ? 'selected' : '' }}>Khalti</option>
                                <option value="connect_ips" {{ old('payment_method') == 'connect_ips' ? 'selected' : '' }}>ConnectIPS</option>
                            </select>
                            @error('payment_method') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="transaction_id" class="block text-sm font-medium text-gray-700 mb-1.5">Transaction ID</label>
                            <input type="text" id="transaction_id" name="transaction_id" value="{{ old('transaction_id') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition @error('transaction_id') border-red-500 @enderror"
                                   placeholder="TXN-12345">
                            @error('transaction_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition @error('status') border-red-500 @enderror"
                                    required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ old('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                            @error('status') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1.5">Payment Date</label>
                        <input type="datetime-local" id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d\TH:i')) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition">
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.payments.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-emerald-200 transition-all hover:shadow-xl">
                        <i class="fa-solid fa-check mr-1.5"></i> Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection