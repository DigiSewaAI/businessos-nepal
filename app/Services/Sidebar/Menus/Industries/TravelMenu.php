<?php

namespace App\Services\Sidebar\Menus\Industries;

class TravelMenu
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
                'label' => 'Bookings',
                'icon' => 'fa-ticket',
                'route' => 'travel.bookings.index',
                'active' => 'travel.bookings.*',
                'permission' => null,
                'badge' => 'New',
            ],
            [
                'label' => 'Packages',
                'icon' => 'fa-suitcase',
                'route' => 'travel.packages.index',
                'active' => 'travel.packages.*',
                'permission' => null,
            ],
            [
                'label' => 'Destinations',
                'icon' => 'fa-location-dot',
                'route' => 'travel.destinations.index',
                'active' => 'travel.destinations.*',
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