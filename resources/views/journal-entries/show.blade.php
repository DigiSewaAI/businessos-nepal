@extends('layouts.app')

@section('title', 'Journal Entry #' . $journalEntry->id)

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('journal-entries.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Journal Entry #{{ $journalEntry->id }}</h1>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-gray-500">Date</p>
                    <p class="font-semibold">{{ $journalEntry->date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Created By</p>
                    <p class="font-semibold">{{ $journalEntry->createdBy?->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Reference</p>
                    <p class="font-semibold">{{ $journalEntry->reference_type ?? 'Manual' }} {{ $journalEntry->reference_id ? '#'.$journalEntry->reference_id : '' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Description</p>
                    <p class="font-semibold">{{ $journalEntry->description }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Lines</h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Debit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Credit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($journalEntry->lines as $line)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ $line->account->code }} - {{ $line->account->name }}</td>
                            <td class="px-6 py-4 text-sm text-blue-600">{{ number_format($line->debit, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-green-600">{{ number_format($line->credit, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $line->description ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t border-gray-200">
                    <tr>
                        <td class="px-6 py-3 font-semibold text-gray-700">Total</td>
                        <td class="px-6 py-3 font-bold text-blue-600">{{ number_format($journalEntry->lines->sum('debit'), 2) }}</td>
                        <td class="px-6 py-3 font-bold text-green-600">{{ number_format($journalEntry->lines->sum('credit'), 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection