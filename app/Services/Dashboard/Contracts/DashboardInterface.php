<?php

namespace App\Services\Dashboard\Contracts;

interface DashboardInterface
{
    /**
     * Get the view name for this dashboard.
     */
    public function getView(): string;

    /**
     * Get the data for the dashboard view.
     */
    public function getData(): array;

    /**
     * Get the industry this dashboard belongs to.
     */
    public function getIndustry(): string;

    /**
     * Get the business category this dashboard supports.
     */
    public function getBusinessCategories(): array;
}