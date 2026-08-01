<?php

namespace App\Services\Sidebar\Menus\Industries;

class SuperAdminMenu
{
    public function getItems($user): array
    {
        return [
            ['label' => 'Dashboard', 'icon' => 'fa-gauge-high', 'route' => 'dashboard', 'active' => 'dashboard'],
            ['label' => 'Organizations', 'icon' => 'fa-building', 'route' => '#', 'active' => 'admin.organizations.*'],
            ['label' => 'Subscriptions', 'icon' => 'fa-credit-card', 'route' => '#', 'active' => 'admin.subscriptions.*'],
            ['label' => 'Plans', 'icon' => 'fa-tags', 'route' => '#', 'active' => 'admin.plans.*'],
            ['label' => 'Payments', 'icon' => 'fa-money-bill-wave', 'route' => '#', 'active' => 'admin.payments.*'],
            ['label' => 'Users', 'icon' => 'fa-users', 'route' => '#', 'active' => 'admin.users.*'],
            ['label' => 'Roles', 'icon' => 'fa-user-shield', 'route' => '#', 'active' => 'admin.roles.*'],
            ['label' => 'Support Tickets', 'icon' => 'fa-headset', 'route' => '#', 'active' => 'admin.support.*', 'badge' => 'New'],
            ['label' => 'CMS', 'icon' => 'fa-newspaper', 'route' => '#', 'active' => 'admin.cms.*'],
            ['label' => 'AI', 'icon' => 'fa-robot', 'route' => 'ai.chat', 'active' => 'ai.*', 'badge' => 'New'],
            ['label' => 'Analytics', 'icon' => 'fa-chart-line', 'route' => '#', 'active' => 'admin.analytics.*'],
            ['label' => 'Logs', 'icon' => 'fa-file-lines', 'route' => '#', 'active' => 'admin.logs.*'],
            ['label' => 'Settings', 'icon' => 'fa-gear', 'route' => '#', 'active' => 'admin.settings.*'],
            ['label' => 'Backups', 'icon' => 'fa-database', 'route' => '#', 'active' => 'admin.backups.*'],
        ];
    }
}