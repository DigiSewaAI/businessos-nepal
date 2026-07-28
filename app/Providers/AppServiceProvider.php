<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Organization;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Scopes\OrganizationScope;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Organization::addGlobalScope(new OrganizationScope());
        Branch::addGlobalScope(new OrganizationScope());
        Category::addGlobalScope(new OrganizationScope());
        Brand::addGlobalScope(new OrganizationScope());
        Unit::addGlobalScope(new OrganizationScope());
        Warehouse::addGlobalScope(new OrganizationScope());
        Product::addGlobalScope(new OrganizationScope());
    }
}