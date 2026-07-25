<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Organization;
use App\Models\Branch;
use App\Models\User;
use App\Models\Scopes\OrganizationScope;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Apply OrganizationScope to all tenant models
        Organization::addGlobalScope(new OrganizationScope());
        Branch::addGlobalScope(new OrganizationScope());
        // Add other models later (Product, Supplier, etc.)
    }
}