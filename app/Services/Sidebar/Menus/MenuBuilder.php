<?php

namespace App\Services\Sidebar\Menus;

use App\Services\Sidebar\Menus\Industries\RetailMenu;
use App\Services\Sidebar\Menus\Industries\SchoolMenu;
use App\Services\Sidebar\Menus\Industries\RestaurantMenu;
use App\Services\Sidebar\Menus\Industries\TravelMenu;

class MenuBuilder
{
    protected $menus = [
        'retail' => RetailMenu::class,
        'school' => SchoolMenu::class,
        'restaurant' => RestaurantMenu::class,
       // 'travel' => TravelMenu::class,
        // Add more industries as needed
    ];

    public function build(string $industry, $user): array
    {
        $industry = $industry ?? 'retail';

        if (!isset($this->menus[$industry])) {
            $industry = 'retail';
        }

        $menuClass = $this->menus[$industry];
        $menu = new $menuClass();

        return $menu->getItems($user);
    }
}