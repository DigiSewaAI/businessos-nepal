@extends('layouts.admin')

@section('title', 'Journal Entries')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Journal Entries</h1>
            <a href="{{ route('journal-entries.create') }}" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="fa-solid fa-plus"></i> New Entry
            </a>
        </div>

        <form method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6 flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs text-gray-500">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500">Account</label>
                <select name="account_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">All</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->code }} - {{ $account->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
        </form>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Debit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Credit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($entries as $entry)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ $entry->date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $entry->description }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-blue-600">{{ number_format($entry->lines->sum('debit'), 2) }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-green-600">{{ number_format($entry->lines->sum('credit'), 2) }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('journal-entries.show', $entry) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">No entries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $entries->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
