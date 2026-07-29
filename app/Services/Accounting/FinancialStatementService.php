<?php

namespace App\Services\Accounting;

use App\Models\Account;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;

class FinancialStatementService
{
    public function getIncomeStatement($startDate, $endDate)
    {
        $orgId = auth()->user()->organization_id;

        // Revenue accounts
        $revenue = Account::where('organization_id', $orgId)
            ->where('type', 'revenue')
            ->where('is_active', true)
            ->get()
            ->map(function ($account) use ($startDate, $endDate) {
                $balance = $account->journalEntries()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->sum('credit') - $account->journalEntries()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->sum('debit');
                return ['name' => $account->name, 'amount' => $balance];
            });

        // Expense accounts
        $expenses = Account::where('organization_id', $orgId)
            ->where('type', 'expense')
            ->where('is_active', true)
            ->get()
            ->map(function ($account) use ($startDate, $endDate) {
                $balance = $account->journalEntries()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->sum('debit') - $account->journalEntries()
                    ->whereBetween('date', [$startDate, $endDate])
                    ->sum('credit');
                return ['name' => $account->name, 'amount' => $balance];
            });

        $totalRevenue = $revenue->sum('amount');
        $totalExpenses = $expenses->sum('amount');
        $netIncome = $totalRevenue - $totalExpenses;

        return [
            'revenue' => $revenue,
            'expenses' => $expenses,
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'net_income' => $netIncome,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    public function getBalanceSheet($asOfDate)
    {
        $orgId = auth()->user()->organization_id;

        $assets = Account::where('organization_id', $orgId)
            ->where('type', 'asset')
            ->where('is_active', true)
            ->get()
            ->map(function ($account) use ($asOfDate) {
                $balance = $account->balance; // uses model attribute
                return ['name' => $account->name, 'amount' => $balance];
            });

        $liabilities = Account::where('organization_id', $orgId)
            ->where('type', 'liability')
            ->where('is_active', true)
            ->get()
            ->map(function ($account) use ($asOfDate) {
                $balance = $account->balance;
                return ['name' => $account->name, 'amount' => $balance];
            });

        $equity = Account::where('organization_id', $orgId)
            ->where('type', 'equity')
            ->where('is_active', true)
            ->get()
            ->map(function ($account) use ($asOfDate) {
                $balance = $account->balance;
                return ['name' => $account->name, 'amount' => $balance];
            });

        $totalAssets = $assets->sum('amount');
        $totalLiabilities = $liabilities->sum('amount');
        $totalEquity = $equity->sum('amount');

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'total_assets' => $totalAssets,
            'total_liabilities' => $totalLiabilities,
            'total_equity' => $totalEquity,
            'as_of_date' => $asOfDate,
        ];
    }
}