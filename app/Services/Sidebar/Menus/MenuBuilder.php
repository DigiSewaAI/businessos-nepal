<?php

namespace App\Services\Sidebar\Menus;

use App\Services\Sidebar\Menus\Industries\RetailMenu;
use App\Services\Sidebar\Menus\Industries\SchoolMenu;
use App\Services\Sidebar\Menus\Industries\RestaurantMenu;
use App\Services\Sidebar\Menus\Industries\TravelMenu;
use App\Services\Sidebar\Menus\Industries\SuperAdminMenu;
use App\Services\Sidebar\Menus\Industries\HospitalMenu;
use App\Services\Sidebar\Menus\Industries\NGOMenu;
use App\Services\Sidebar\Menus\Industries\ManufacturingMenu;
use App\Services\Sidebar\Menus\Industries\ServiceMenu;

class MenuBuilder
{
    protected $menus = [
        'retail' => RetailMenu::class,
        'school' => SchoolMenu::class,
        'restaurant' => RestaurantMenu::class,
        'travel' => TravelMenu::class,
        'super_admin' => SuperAdminMenu::class,
        'hospital' => HospitalMenu::class,
        'ngo' => NGOMenu::class,
        'manufacturing' => ManufacturingMenu::class,
        'service' => ServiceMenu::class,
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