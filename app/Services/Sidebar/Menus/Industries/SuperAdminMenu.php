<?php

namespace App\Services\Sidebar\Menus\Industries;

class SuperAdminMenu
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
                'label' => 'Organizations',
                'icon' => 'fa-building',
                'route' => 'admin.organizations.index',
                'active' => 'admin.organizations.*',
                'permission' => null,
            ],
            [
                'label' => 'Users',
                'icon' => 'fa-users',
                'route' => 'admin.users.index',
                'active' => 'admin.users.*',
                'permission' => null,
            ],
            [
                'label' => 'Reports',
                'icon' => 'fa-chart-pie',
                'route' => 'admin.reports.index',
                'active' => 'admin.reports.*',
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