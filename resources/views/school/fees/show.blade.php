@extends('layouts.admin')

@section('title', 'Fee Summary')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('school.fees.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Fee Summary</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500">Total Fees</p>
                <p class="text-xl font-bold">Rs. {{ number_format($summary['total'], 2) }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500">Paid</p>
                <p class="text-xl font-bold text-green-600">Rs. {{ number_format($summary['paid'], 2) }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500">Due</p>
                <p class="text-xl font-bold text-red-600">Rs. {{ number_format($summary['due'], 2) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">All Invoices</h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fee Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paid</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($summary['invoices'] as $invoice)
                    <tr>
                        <td class="px-6 py-4 text-sm font-mono">{{ $invoice->invoice_number }}</td>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 
