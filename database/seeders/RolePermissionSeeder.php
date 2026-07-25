<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions (Granular)
        $permissions = [
            'view_dashboard',
            'manage_products', 'view_products', 'create_products', 'edit_products', 'delete_products',
            'manage_stock', 'view_stock', 'adjust_stock',
            'manage_sales', 'create_sales', 'view_sales', 'edit_sales', 'delete_sales',
            'manage_purchases', 'create_purchases', 'view_purchases', 'edit_purchases', 'delete_purchases',
            'manage_expenses', 'view_expenses', 'create_expenses',
            'manage_users', 'view_users', 'create_users', 'edit_users', 'delete_users',
            'manage_roles', 'view_roles', 'edit_roles',
            'view_reports',
            'manage_settings',
            'view_audit_logs',
        ];

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        // Create roles and assign permissions
        // 1. Owner (Full Access)
        $owner = Role::create(['name' => 'Owner']);
        $owner->givePermissionTo(Permission::all());

        // 2. Manager (Almost everything except user/role management)
        $manager = Role::create(['name' => 'Manager']);
        $manager->givePermissionTo([
            'view_dashboard',
            'view_products', 'create_products', 'edit_products',
            'view_stock', 'adjust_stock',
            'create_sales', 'view_sales',
            'create_purchases', 'view_purchases',
            'view_expenses', 'create_expenses',
            'view_users',
            'view_reports',
        ]);

        // 3. Cashier (Only Sales)
        $cashier = Role::create(['name' => 'Cashier']);
        $cashier->givePermissionTo([
            'view_dashboard',
            'view_products',
            'view_stock',
            'create_sales', 'view_sales',
        ]);

        // 4. Inventory (Stock & Products only)
        $inventory = Role::create(['name' => 'Inventory Clerk']);
        $inventory->givePermissionTo([
            'view_dashboard',
            'view_products', 'create_products', 'edit_products',
            'view_stock', 'adjust_stock',
            'view_purchases', 'create_purchases',
        ]);
    }
}