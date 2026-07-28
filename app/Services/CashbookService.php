<?php

namespace App\Services;

use App\Models\Cashbook;
use App\Models\Sale;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class CashbookService
{
    /**
     * Get or create today's cashbook
     */
    public function getTodayCashbook()
    {
        $today = now()->toDateString();
        $branchId = auth()->user()->branch_id;

        $cashbook = Cashbook::where('date', $today)
            ->where('branch_id', $branchId)
            ->first();

        if (!$cashbook) {
            $openingBalance = $this->getPreviousDayClosingBalance($branchId);
            $cashbook = Cashbook::create([
                'organization_id' => auth()->user()->organization_id,
                'branch_id' => $branchId,
                'date' => $today,
                'opening_balance' => $openingBalance,
                'closing_balance' => $openingBalance,
                'status' => 'open',
                'created_by' => auth()->id(),
            ]);
        }

        return $cashbook;
    }

    /**
     * Update cashbook with sales and expenses
     */
    public function updateCashbook()
    {
        $cashbook = $this->getTodayCashbook();

        // Calculate total cash sales
        $cashSales = Sale::where('branch_id', $cashbook->branch_id)
            ->whereDate('created_at', $cashbook->date)
            ->where('payment_method', 'cash')
            ->where('status', 'completed')
            ->sum('total');

        // Calculate total cash expenses
        $cashExpenses = Expense::where('branch_id', $cashbook->branch_id)
            ->whereDate('date', $cashbook->date)
            ->where('payment_method', 'cash')
            ->sum('amount');

        $cashbook->total_cash_in = $cashSales;
        $cashbook->total_cash_out = $cashExpenses;
        $cashbook->closing_balance = $cashbook->opening_balance + $cashSales - $cashExpenses;
        $cashbook->save();

        return $cashbook;
    }

    /**
     * Get previous day's closing balance
     */
    private function getPreviousDayClosingBalance($branchId)
    {
        $yesterday = now()->subDay()->toDateString();
        $prev = Cashbook::where('branch_id', $branchId)
            ->where('date', $yesterday)
            ->first();

        return $prev ? $prev->closing_balance : 0;
    }

    /**
     * Close today's cashbook
     */
    public function closeCashbook($physicalCount, $notes = null)
    {
        $cashbook = $this->getTodayCashbook();
        $cashbook->physical_count = $physicalCount;
        $cashbook->variance = $physicalCount - $cashbook->closing_balance;
        $cashbook->notes = $notes;
        $cashbook->status = 'closed';
        $cashbook->save();

        return $cashbook;
    }
}