<?php

namespace App\Services;

use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    /**
     * Record a journal entry
     */
    public function recordEntry($referenceType, $referenceId, $entries, $description = null)
    {
        return DB::transaction(function () use ($referenceType, $referenceId, $entries, $description) {
            foreach ($entries as $entry) {
                JournalEntry::create([
                    'organization_id' => auth()->user()->organization_id,
                    'branch_id' => auth()->user()->branch_id,
                    'date' => now(),
                    'reference_type' => $referenceType,
                    'reference_id' => $referenceId,
                    'account' => $entry['account'],
                    'debit' => $entry['debit'] ?? 0,
                    'credit' => $entry['credit'] ?? 0,
                    'description' => $description,
                    'created_by' => auth()->id(),
                ]);
            }

            return true;
        });
    }

    /**
     * Get account balance
     */
    public function getAccountBalance($account)
    {
        $debit = JournalEntry::where('account', $account)->sum('debit');
        $credit = JournalEntry::where('account', $account)->sum('credit');

        return $debit - $credit;
    }

    /**
     * Get trial balance
     */
    public function getTrialBalance()
    {
        return JournalEntry::select('account')
            ->selectRaw('SUM(debit) as total_debit')
            ->selectRaw('SUM(credit) as total_credit')
            ->groupBy('account')
            ->get();
    }
}