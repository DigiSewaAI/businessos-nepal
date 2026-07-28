<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Cashbook;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::orderBy('date', 'desc')->paginate(20);
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'payment_method' => 'required|in:cash,bank,mobile',
            'reference_no' => 'nullable|string|max:100',
        ]);

        $validated['organization_id'] = auth()->user()->organization_id;
        $validated['created_by'] = auth()->id();

        $expense = Expense::create($validated);

        // Update cashbook
        if ($validated['payment_method'] == 'cash') {
            app(\App\Services\CashbookService::class)->updateCashbook();
        }

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted.');
    }
}