<?php

namespace App\Services\Sidebar\Menus\Industries;

class RetailMenu
{
    public function getItems($user): array
    {
        return [
            [
                'label' => 'Dashboard',
                'icon' => 'fa-gauge-high',
                'route' => 'dashboard',
                'active' => 'dashboard',
                'permission' => null,
            ],
            [
                'label' => 'POS',
                'icon' => 'fa-cash-register',
                'route' => 'sales.pos',
                'active' => 'sales.pos',
                'permission' => null,
            ],
            [
                'label' => 'Products',
                'icon' => 'fa-boxes-stacked',
                'route' => 'products.index',
                'active' => 'products.*',
                'permission' => null,
            ],
            [
                'label' => 'Purchases',
                'icon' => 'fa-truck',
                'route' => 'purchases.index',
                'active' => 'purchases.*',
                'permission' => null,
            ],
            [
                'label' => 'Expenses',
                'icon' => 'fa-wallet',
                'route' => 'expenses.index',
                'active' => 'expenses.*',
                'permission' => null,
            ],
            [
                'label' => 'Reports',
                'icon' => 'fa-chart-pie',
                'route' => 'reports.sales',
                'active' => 'reports.*',
                'permission' => null,
            ],
            [
                'label' => 'AI Assistant',
                'icon' => 'fa-robot',
                'route' => 'ai.chat',
                'active' => 'ai.*',
                'permission' => null,
                'badge' => 'New',
            ],
        ];
    }
}