<?php

namespace App\Services\Sidebar\Menus\Industries;

class RestaurantMenu
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
                'label' => 'Orders',
                'icon' => 'fa-clipboard-list',
                'route' => 'restaurant.orders.index',
                'active' => 'restaurant.orders.*',
                'permission' => null,
                'badge' => 'Live',
            ],
            [
                'label' => 'Tables',
                'icon' => 'fa-utensils',
                'route' => 'restaurant.tables.layout',
                'active' => 'restaurant.tables.*',
                'permission' => null,
            ],
            [
                'label' => 'Kitchen (KOT)',
                'icon' => 'fa-fire',
                'route' => 'restaurant.kitchen',
                'active' => 'restaurant.kitchen',
                'permission' => null,
            ],
            [
                'label' => 'Menu',
                'icon' => 'fa-book-open',
                'route' => 'products.index',
                'active' => 'products.*',
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