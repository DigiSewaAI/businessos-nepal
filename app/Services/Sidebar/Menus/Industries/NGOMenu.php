<?php

namespace App\Services\Sidebar\Menus\Industries;

class NGOMenu
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
                'label' => 'Projects',
                'icon' => 'fa-diagram-project',
                'route' => 'ngo.projects.index',
                'active' => 'ngo.projects.*',
                'permission' => null,
                'badge' => 'New',
            ],
            [
                'label' => 'Donations',
                'icon' => 'fa-hand-holding-heart',
                'route' => 'ngo.donations.index',
                'active' => 'ngo.donations.*',
                'permission' => null,
                'badge' => 'New',
            ],
            [
                'label' => 'Beneficiaries',
                'icon' => 'fa-users',
                'route' => 'ngo.beneficiaries.index',
                'active' => 'ngo.beneficiaries.*',
                'permission' => null,
            ],
            [
                'label' => 'Expenses',
                'icon' => 'fa-wallet',
                'route' => 'ngo.expenses.index',
                'active' => 'ngo.expenses.*',
                'permission' => null,
            ],
            [
                'label' => 'Reports',
                'icon' => 'fa-chart-pie',
                'route' => 'ngo.reports.index',
                'active' => 'ngo.reports.*',
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