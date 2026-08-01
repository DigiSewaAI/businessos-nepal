@extends('layouts.admin')

@section('title', 'New Expense - BusinessOS Nepal')

@section('content')
<div class="pt-4 pb-4 px-4 bg-gray-50 min-h-screen">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Record Expense</h1>
            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <input type="text" name="category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="e.g. Utilities, Rent, Salary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Expense title">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" class="w-full px-4 py-2 border border-gray-300 rounded-lg" rows="2"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount (Rs.)</label>
                        <input type="number" name="amount" step="0.01" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="cash">Cash</option>
                            <option value="bank">Bank</option>
                            <option value="mobile">Mobile</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Reference No (Optional)</label>
                        <input type="text" name="reference_no" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <button type="submit" class="w-full gradient-bg text-white py-3 rounded-xl font-bold hover:shadow-lg transition-all">
                        <i class="fa-solid fa-save"></i> Save Expense
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
