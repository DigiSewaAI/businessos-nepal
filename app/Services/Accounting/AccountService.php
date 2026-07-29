<?php

namespace App\Services\Accounting;

use App\Models\Account;
use Illuminate\Support\Facades\DB;

class AccountService
{
    public function create(array $data)
    {
        $data['organization_id'] = auth()->user()->organization_id;
        return Account::create($data);
    }

    public function update(Account $account, array $data)
    {
        $account->update($data);
        return $account;
    }

    public function delete(Account $account)
    {
        if ($account->journalEntries()->exists()) {
            throw new \Exception('Cannot delete account with journal entries.');
        }
        $account->delete();
    }

    public function getChartOfAccounts()
    {
        return Account::with('children')
            ->whereNull('parent_id')
            ->orderBy('code')
            ->get();
    }
}