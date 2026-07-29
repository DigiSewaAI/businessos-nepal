@extends('layouts.app')

@section('title', $account->name)

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center mb-6">
            <a href="{{ route('accounts.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $account->code }} - {{ $account->name }}</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <p class="text-sm text-gray-500">Account Type</p>
                <p class="text-xl font-semibold capitalize">{{ $account->type }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <p class="text-sm text-gray-500">Current Balance</p>
                <p class="text-xl font-bold {{ $account->balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($account->balance, 2) }}
                </p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <p class="text-sm text-gray-500">Opening Balance</p>
                <p class="text-xl font-semibold">{{ number_format($account->opening_balance, 2) }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <p class="text-sm text-gray-500">Status</p>
                <span class="px-3 py-1 rounded-full text-sm font-semibold 
                    {{ $account->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                    {{ $account->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Journal Entries</h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Debit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Credit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($account->journalEntries as $entry)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ $entry->date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $entry->description }}</td>
                            <td class="px-6 py-4 text-sm text-blue-600">{{ number_format($entry->debit, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-green-600">{{ number_format($entry->credit, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400">No entries found for this account.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection