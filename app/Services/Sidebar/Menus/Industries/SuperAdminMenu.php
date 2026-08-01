<?php

namespace App\Services\Sidebar\Menus\Industries;

class SuperAdminMenu
{
    public function getItems($user): array
    {
        return [
            ['label' => 'Dashboard', 'icon' => 'fa-gauge-high', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
            ['label' => 'Organizations', 'icon' => 'fa-building', 'route' => 'admin.organizations.index', 'active' => 'admin.organizations.*'],
            ['label' => 'Subscriptions', 'icon' => 'fa-credit-card', 'route' => 'admin.subscriptions.index', 'active' => 'admin.subscriptions.*'],
            ['label' => 'Plans', 'icon' => 'fa-tags', 'route' => 'admin.plans.index', 'active' => 'admin.plans.*'],
            ['label' => 'Payments', 'icon' => 'fa-money-bill-wave', 'route' => 'admin.payments.index', 'active' => 'admin.payments.*'],
            ['label' => 'Users', 'icon' => 'fa-users', 'route' => 'admin.users.index', 'active' => 'admin.users.*'],
            ['label' => 'Roles', 'icon' => 'fa-user-shield', 'route' => 'admin.roles.index', 'active' => 'admin.roles.*'],
            ['label' => 'Support Tickets', 'icon' => 'fa-headset', 'route' => 'admin.support.index', 'active' => 'admin.support.*', 'badge' => 'New'],
            ['label' => 'CMS', 'icon' => 'fa-newspaper', 'route' => 'admin.cms.index', 'active' => 'admin.cms.*'],
            ['label' => 'AI', 'icon' => 'fa-robot', 'route' => 'ai.dashboard', 'active' => 'ai.*', 'badge' => 'New'],
            ['label' => 'Analytics', 'icon' => 'fa-chart-line', 'route' => 'admin.analytics.index', 'active' => 'admin.analytics.*'],
            ['label' => 'Logs', 'icon' => 'fa-file-lines', 'route' => 'admin.logs.index', 'active' => 'admin.logs.*'],
            ['label' => 'Settings', 'icon' => 'fa-gear', 'route' => 'admin.settings.index', 'active' => 'admin.settings.*'],
            ['label' => 'Backups', 'icon' => 'fa-database', 'route' => 'admin.backups.index', 'active' => 'admin.backups.*'],
        ];
    }
}