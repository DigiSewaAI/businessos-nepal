<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'price_monthly' => 0,
                'price_yearly' => 0,
                'features' => [
                    'max_products' => 100,
                    'max_users' => 2,
                    'max_branches' => 1,
                    'max_warehouses' => 1,
                    'max_storage_mb' => 10,
                    'has_reports' => true,
                    'has_pos' => true,
                    'has_purchase' => false,
                    'has_finance' => false,
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'price_monthly' => 999,
                'price_yearly' => 9990,
                'features' => [
                    'max_products' => -1, // unlimited
                    'max_users' => -1,
                    'max_branches' => -1,
                    'max_warehouses' => -1,
                    'max_storage_mb' => 100,
                    'has_reports' => true,
                    'has_pos' => true,
                    'has_purchase' => true,
                    'has_finance' => true,
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price_monthly' => 2999,
                'price_yearly' => 29990,
                'features' => [
                    'max_products' => -1,
                    'max_users' => -1,
                    'max_branches' => -1,
                    'max_warehouses' => -1,
                    'max_storage_mb' => 500,
                    'has_reports' => true,
                    'has_pos' => true,
                    'has_purchase' => true,
                    'has_finance' => true,
                    'has_api' => true,
                    'priority_support' => true,
                ],
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}