<?php
namespace App\Services\AI\Agent;

use Illuminate\Support\Facades\Auth;

class PermissionGate
{
    public function canExecute(string $action): bool
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name')->toArray();

        $permissions = [
            'view_stock' => ['owner', 'manager', 'cashier', 'staff'],
            'view_sales' => ['owner', 'manager', 'cashier'],
            'view_profit' => ['owner', 'manager'],
            'create_invoice' => ['owner', 'manager', 'cashier'],
            'delete_invoice' => ['owner', 'manager'],
            'add_product' => ['owner', 'manager'],
            'update_stock' => ['owner', 'manager', 'cashier'],
            'delete_product' => ['owner'],
            'view_attendance' => ['owner', 'manager', 'teacher'],
            'view_fees' => ['owner', 'manager', 'accountant'],
            'view_restaurant_orders' => ['owner', 'manager', 'waiter'],
            'kitchen_actions' => ['owner', 'manager', 'kitchen_staff'],
        ];

        $allowedRoles = $permissions[$action] ?? [];
        
        foreach ($roles as $role) {
            if (in_array($role, $allowedRoles)) {
                return true;
            }
        }

        return false;
    }

    public function filterContext(array $context, string $role): array
    {
        if ($role === 'cashier') {
            unset($context['profit'], $context['expenses']);
        }

        if ($role === 'staff' || $role === 'waiter') {
            unset($context['profit'], $context['expenses'], $context['sales']);
        }

        return $context;
    }
}