<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'Lite',
                'slug' => 'lite',
                'price_monthly' => 300,
                'price_yearly' => 2500,
                'trial_days' => 0,
                'max_products' => 300,
                'max_branches' => 1,
                'max_users' => 3,
                'max_invoices_monthly' => 400,
                'max_storage_mb' => 200,
                'max_ai_requests' => 50,
                'has_purchase' => false,
                'has_finance' => false,
                'has_api' => false,
                'has_white_label' => false,
                'backup_frequency' => 'weekly',
                'is_active' => true,
                'is_popular' => false,
                'perfect_for' => json_encode(['Kirana', 'Pharmacy', 'Bakery', 'Mobile Shop', 'Cosmetics', 'Gift Shop']),
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'price_monthly' => 999,
                'price_yearly' => 9999,
                'trial_days' => 14,
                'max_products' => null,
                'max_branches' => 3,
                'max_users' => 20,
                'max_invoices_monthly' => 5000,
                'max_storage_mb' => 2048,
                'max_ai_requests' => 1000,
                'has_purchase' => true,
                'has_finance' => true,
                'has_api' => true,
                'has_white_label' => false,
                'backup_frequency' => 'daily',
                'is_active' => true,
                'is_popular' => true,
                'perfect_for' => json_encode(['Restaurant', 'School', 'Hardware', 'Electronics', 'Fashion Store', 'Multi-Branch Retail']),
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'price_monthly' => 2999,
                'price_yearly' => 29999,
                'trial_days' => 14,
                'max_products' => null,
                'max_branches' => 10,
                'max_users' => 100,
                'max_invoices_monthly' => 25000,
                'max_storage_mb' => 10240,
                'max_ai_requests' => 5000,
                'has_purchase' => true,
                'has_finance' => true,
                'has_api' => true,
                'has_white_label' => false,
                'backup_frequency' => 'realtime',
                'is_active' => true,
                'is_popular' => false,
                'perfect_for' => json_encode(['Wholesale', 'Distribution', 'Hospital', 'Large School', 'Multi-location Businesses']),
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price_monthly' => null,
                'price_yearly' => null,
                'trial_days' => 0,
                'max_products' => null,
                'max_branches' => null,
                'max_users' => null,
                'max_invoices_monthly' => null,
                'max_storage_mb' => null,
                'max_ai_requests' => null,
                'has_purchase' => true,
                'has_finance' => true,
                'has_api' => true,
                'has_white_label' => true,
                'backup_frequency' => 'custom',
                'is_active' => true,
                'is_popular' => false,
                'perfect_for' => json_encode(['Supermarket Chains', 'Franchises', 'Manufacturing', 'Corporate Groups']),
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}