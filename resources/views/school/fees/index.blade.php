@extends('layouts.app')

@section('title', 'Fee Invoices')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Fee Invoices</h1>
            <a href="{{ route('school.students.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="fa-solid fa-user-plus"></i> Select Student
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fee Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paid</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($invoices as $invoice)
                    <tr>
                        <td class="px-6 py-4 text-sm font-mono">{{ $invoice->invoice_number }}</td>
                        <td class="px-6 py-4 text-sm">{{ $invoice->student->full_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $invoice->feeStructure->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm font-semibold">Rs. {{ number_format($invoice->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm">Rs. {{ number_format($invoice->paid_amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $invoice->status == 'partial' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $invoice->status == 'unpaid' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $invoice->due_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($invoice->status != 'paid')
                            <button onclick="showPaymentModal({{ $invoice->id }})" class="text-green-600 hover:text-green-800">
                                <i class="fa-solid fa-money-bill"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-6 py-12 text-center text-gray-400">No invoices found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Pay Invoice</h3>
        <form action="" method="POST" id="paymentForm">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                <input type="number" step="0.01" name="amount" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                <select name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                    <option value="cash">Cash</option>
                    <option value="bank">Bank Transfer</option>
                    <option value="card">Card</option>
                    <option value="online">Online</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" class="w-full px-4 py-2 border border-gray-300 rounded-lg" rows="2"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closePaymentModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="gradient-bg text-white px-4 py-2 rounded-lg font-semibold">Pay Now</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showPaymentModal(invoiceId) {
        document.getElementById('paymentForm').action = '/school/fees/pay/' + invoiceId;
        document.getElementById('paymentModal').classList.remove('hidden');
        document.getElementById('paymentModal').classList.add('flex');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
        document.getElementById('paymentModal').classList.remove('flex');
    }
</script>
@endsection 
