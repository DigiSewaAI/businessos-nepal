<?php

namespace App\Services\Dashboard;

use App\Services\Dashboard\Contracts\DashboardInterface;

abstract class BaseDashboard implements DashboardInterface
{
    protected $organizationId;
    protected $industry;
    protected $businessCategory;

    public function __construct(int $organizationId, string $industry, ?string $businessCategory = null)
    {
        $this->organizationId = $organizationId;
        $this->industry = $industry;
        $this->businessCategory = $businessCategory;
    }

    public function getIndustry(): string
    {
        return $this->industry;
    }

    public function getBusinessCategories(): array
    {
        return [];
    }

    /**
     * Get the view name (can be overridden by child classes).
     */
    public function getView(): string
    {
        return 'dashboard.industries.' . $this->industry;
    }

    /**
     * Get stats data with safe fallback.
     */
    protected function getStats(array $metrics): array
    {
        return array_merge([
            'today_sales' => 0,
            'today_profit' => 0,
            'low_stock' => 0,
            'cash_balance' => 0,
        ], $metrics);
    }
}