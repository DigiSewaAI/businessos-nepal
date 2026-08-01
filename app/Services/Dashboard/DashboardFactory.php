<?php

namespace App\Services\Dashboard;

use App\Services\Dashboard\Dashboards\RetailDashboard;
use App\Services\Dashboard\Dashboards\SchoolDashboard;
use App\Services\Dashboard\Dashboards\RestaurantDashboard;
use App\Services\Dashboard\Dashboards\TravelDashboard;
use App\Services\Dashboard\Dashboards\HospitalDashboard;
use App\Services\Dashboard\Dashboards\NGODashboard;
use App\Services\Dashboard\Dashboards\ManufacturingDashboard;
use App\Services\Dashboard\Dashboards\ServiceDashboard;
use App\Services\Dashboard\Contracts\DashboardInterface;
use Illuminate\Support\Facades\Log;

class DashboardFactory
{
    /**
     * Mapping of industries to their dashboard classes.
     */
    protected array $dashboards = [
        'retail' => RetailDashboard::class,
        'school' => SchoolDashboard::class,
        'restaurant' => RestaurantDashboard::class,
        'travel' => TravelDashboard::class,
        'hospital' => HospitalDashboard::class,
        'ngo' => NGODashboard::class,
        'manufacturing' => ManufacturingDashboard::class,
        'service' => ServiceDashboard::class,
    ];

    /**
     * Create a dashboard instance for the given industry.
     */
    public function create(int $organizationId, string $industry, ?string $businessCategory = null): DashboardInterface
    {
        $industry = $industry ?? 'retail';

        // ✅ Check if industry is mapped AND the class actually exists
        if (!isset($this->dashboards[$industry]) || !class_exists($this->dashboards[$industry])) {
            Log::warning("Dashboard class for industry '{$industry}' not found, falling back to Retail", [
                'organization_id' => $organizationId,
            ]);
            $industry = 'retail';
        }

        $class = $this->dashboards[$industry];
        return new $class($organizationId, $industry, $businessCategory);
    }

    /**
     * Get all supported industries (only those with existing classes).
     */
    public function getSupportedIndustries(): array
    {
        return array_filter(
            array_keys($this->dashboards),
            fn($industry) => $this->isSupported($industry)
        );
    }

    /**
     * Check if an industry is supported (mapped and class exists).
     */
    public function isSupported(string $industry): bool
    {
        return isset($this->dashboards[$industry]) && class_exists($this->dashboards[$industry]);
    }
}