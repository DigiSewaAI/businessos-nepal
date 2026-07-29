<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class DefaultAccountSeeder extends Seeder
{
    public function run()
    {
        $orgId = 1; // or get from config

        $accounts = [
            // Assets
            ['code' => '1000', 'name' => 'Cash', 'type' => 'asset'],
            ['code' => '1010', 'name' => 'Bank Account', 'type' => 'asset'],
            ['code' => '1200', 'name' => 'Accounts Receivable', 'type' => 'asset'],
            ['code' => '1500', 'name' => 'Inventory', 'type' => 'asset'],
            ['code' => '1800', 'name' => 'Fixed Assets', 'type' => 'asset'],

            // Liabilities
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'liability'],
            ['code' => '2100', 'name' => 'Bank Loan', 'type' => 'liability'],
            ['code' => '2200', 'name' => 'Tax Payable', 'type' => 'liability'],

            // Equity
            ['code' => '3000', 'name' => 'Owner\'s Equity', 'type' => 'equity'],
            ['code' => '3100', 'name' => 'Retained Earnings', 'type' => 'equity'],

            // Revenue
            ['code' => '4000', 'name' => 'Sales Revenue', 'type' => 'revenue'],
            ['code' => '4100', 'name' => 'Service Revenue', 'type' => 'revenue'],

            // Expenses
            ['code' => '5000', 'name' => 'Cost of Goods Sold', 'type' => 'expense'],
            ['code' => '5100', 'name' => 'Rent Expense', 'type' => 'expense'],
            ['code' => '5200', 'name' => 'Salary Expense', 'type' => 'expense'],
            ['code' => '5300', 'name' => 'Utilities', 'type' => 'expense'],
            ['code' => '5400', 'name' => 'Office Supplies', 'type' => 'expense'],
        ];

        foreach ($accounts as $acc) {
            Account::updateOrCreate(
                ['organization_id' => $orgId, 'code' => $acc['code']],
                $acc + ['organization_id' => $orgId, 'is_active' => true]
            );
        }
    }
}