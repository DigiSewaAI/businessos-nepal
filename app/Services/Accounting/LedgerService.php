<?php

namespace App\Services\Accounting;

use App\Models\Account;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;

class LedgerService
{
    public function getGeneralLedger($startDate = null, $endDate = null)
    {
        $query = JournalEntry::with('lines.account')
            ->where('organization_id', auth()->user()->organization_id);

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        $entries = $query->orderBy('date')->get();

        $ledger = [];
        foreach ($entries as $entry) {
            foreach ($entry->lines as $line) {
                $accountId = $line->account_id;
                if (!isset($ledger[$accountId])) {
                    $ledger[$accountId] = [
                        'account' => $line->account,
                        'entries' => [],
                        'balance' => $line->account->opening_balance ?? 0,
                    ];
                }
                $ledger[$accountId]['entries'][] = [
                    'date' => $entry->date,
                    'description' => $entry->description,
                    'debit' => $line->debit,
                    'credit' => $line->credit,
                    'balance' => 0, // compute progressively
                ];
                // update running balance
                if (in_array($line->account->type, ['asset', 'expense'])) {
                    $ledger[$accountId]['balance'] += $line->debit - $line->credit;
                } else {
                    $ledger[$accountId]['balance'] += $line->credit - $line->debit;
                }
                // store balance in the entry
                $ledger[$accountId]['entries'][count($ledger[$accountId]['entries']) - 1]['balance'] = $ledger[$accountId]['balance'];
            }
        }

        return $ledger;
    }

    public function getTrialBalance($asOfDate = null)
    {
        $asOfDate = $asOfDate ?? now();
        $accounts = Account::where('organization_id', auth()->user()->organization_id)
            ->where('is_active', true)
            ->get();

        $trialBalance = [];
        foreach ($accounts as $account) {
            $balance = $account->balance; // using model attribute
            if ($balance != 0) {
                $trialBalance[] = [
                    'code' => $account->code,
                    'name' => $account->name,
                    'debit' => $balance > 0 && in_array($account->type, ['asset', 'expense']) ? $balance : 0,
                    'credit' => $balance > 0 && in_array($account->type, ['liability', 'equity', 'revenue']) ? $balance : 0,
                ];
            }
        }

        return $trialBalance;
    }
}