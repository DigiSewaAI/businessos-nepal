<?php

namespace App\Services\Sidebar;

use App\Services\Sidebar\Menus\MenuBuilder;
use Illuminate\Support\Facades\Auth;

class SidebarService
{
    protected $menuBuilder;

    public function __construct(MenuBuilder $menuBuilder)
    {
        $this->menuBuilder = $menuBuilder;
    }

    /**
     * Get sidebar menu items for the current user.
     */
    public function getSidebar(): array
    {
        $user = Auth::user();
        $organization = $user->organization;
        $industry = $organization->industry ?? 'retail';

        return $this->menuBuilder->build($industry, $user);
    }
}