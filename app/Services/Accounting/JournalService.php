<?php

namespace App\Services\Accounting;

use App\Models\JournalEntry;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class JournalService
{
    /**
     * Create a journal entry with double-entry validation
     */
    public function createEntry(array $data, array $lines)
    {
        return DB::transaction(function () use ($data, $lines) {
            // Validate total debit = total credit
            $totalDebit = collect($lines)->sum('debit');
            $totalCredit = collect($lines)->sum('credit');
            if ($totalDebit !== $totalCredit) {
                throw new \Exception('Total debit must equal total credit.');
            }

            $entry = JournalEntry::create([
                'organization_id' => auth()->user()->organization_id,
                'branch_id' => auth()->user()->branch_id ?? $data['branch_id'],
                'date' => $data['date'] ?? now(),
                'reference_type' => $data['reference_type'] ?? 'manual',
                'reference_id' => $data['reference_id'] ?? null,
                'description' => $data['description'],
                'created_by' => auth()->id(),
            ]);

            foreach ($lines as $line) {
                $entry->lines()->create([
                    'account_id' => $line['account_id'],
                    'debit' => $line['debit'] ?? 0,
                    'credit' => $line['credit'] ?? 0,
                    'description' => $line['description'] ?? null,
                ]);
            }

            // Update account balances (if we have account_balances table)
            // We'll do this via observer or event listener later.

            return $entry->load('lines.account');
        });
    }

    public function getJournalEntries($filters = [])
    {
        $query = JournalEntry::with('lines.account', 'createdBy')
            ->where('organization_id', auth()->user()->organization_id);

        if (isset($filters['start_date'])) {
            $query->whereDate('date', '>=', $filters['start_date']);
        }
        if (isset($filters['end_date'])) {
            $query->whereDate('date', '<=', $filters['end_date']);
        }
        if (isset($filters['account_id'])) {
            $query->whereHas('lines', function ($q) use ($filters) {
                $q->where('account_id', $filters['account_id']);
            });
        }

        return $query->orderBy('date', 'desc')->paginate(20);
    }
}