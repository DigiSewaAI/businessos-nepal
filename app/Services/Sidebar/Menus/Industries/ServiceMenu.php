<?php

namespace App\Services\Sidebar\Menus\Industries;

class ServiceMenu
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
                'label' => 'Appointments',
                'icon' => 'fa-calendar-plus',
                'route' => 'service.appointments.index',
                'active' => 'service.appointments.*',
                'permission' => null,
                'badge' => 'New',
            ],
            [
                'label' => 'Clients',
                'icon' => 'fa-user-friends',
                'route' => 'service.clients.index',
                'active' => 'service.clients.*',
                'permission' => null,
                'badge' => 'New',
            ],
            [
                'label' => 'Services',
                'icon' => 'fa-hand-sparkles',
                'route' => 'products.index',
                'active' => 'products.*',
                'permission' => null,
            ],
            [
                'label' => 'Staff',
                'icon' => 'fa-user-tie',
                'route' => 'service.staff.index',
                'active' => 'service.staff.*',
                'permission' => null,
            ],
            [
                'label' => 'Billing',
                'icon' => 'fa-file-invoice-dollar',
                'route' => 'service.billing.index',
                'active' => 'service.billing.*',
                'permission' => null,
                'badge' => 'New',
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