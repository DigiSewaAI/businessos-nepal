<?php

namespace App\Services\Sidebar\Menus\Industries;

class ManufacturingMenu
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
                'label' => 'Products',
                'icon' => 'fa-boxes-stacked',
                'route' => 'products.index',
                'active' => 'products.*',
                'permission' => null,
            ],
            [
                'label' => 'Production',
                'icon' => 'fa-industry',
                'route' => 'manufacturing.production.index',
                'active' => 'manufacturing.production.*',
                'permission' => null,
                'badge' => 'New',
            ],
            [
                'label' => 'Raw Materials',
                'icon' => 'fa-cubes',
                'route' => 'manufacturing.raw-materials.index',
                'active' => 'manufacturing.raw-materials.*',
                'permission' => null,
                'badge' => 'New',
            ],
            [
                'label' => 'Orders',
                'icon' => 'fa-truck',
                'route' => 'purchases.index',
                'active' => 'purchases.*',
                'permission' => null,
            ],
            [
                'label' => 'Quality Control',
                'icon' => 'fa-clipboard-check',
                'route' => 'manufacturing.quality.index',
                'active' => 'manufacturing.quality.*',
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