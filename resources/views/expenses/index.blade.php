@extends('layouts.app')

@section('title', 'Expenses - BusinessOS Nepal')

@section('content')
<div class="pt-24 pb-12 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Expenses</h1>
            <a href="{{ route('expenses.create') }}" class="gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold hover:shadow-lg transition-all">
                <i class="fa-solid fa-plus"></i> New Expense
            </a>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($expenses as $expense)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $expense->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $expense->category }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-red-600">Rs. {{ number_format($expense->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $expense->date }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst($expense->payment_method) }}</td>
                        <td class="px-6 py-4 text-sm">
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Delete this expense?')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>
</div>
@endsection