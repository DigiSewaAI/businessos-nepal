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

    public function getSidebar(): array
    {
        $user = Auth::user();

        // ✅ Super Admin check
        if ($user->hasRole('Super Admin')) {
            $industry = 'super_admin';
        } else {
            $organization = $user->organization;
            $industry = $organization->industry ?? 'retail';
        }

        return $this->menuBuilder->build($industry, $user);
    }
}