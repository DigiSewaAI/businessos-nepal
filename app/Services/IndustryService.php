<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class IndustryService
{
    /**
     * Get all industries with their configurations.
     */
    public function getIndustries(): array
    {
        return config('businessos.industries', []);
    }

    /**
     * Get a specific industry configuration.
     */
    public function getIndustry(string $key): ?array
    {
        return $this->getIndustries()[$key] ?? null;
    }

    /**
     * Get business categories for a given industry.
     */
    public function getBusinessCategories(string $industry): array
    {
        $industryData = $this->getIndustry($industry);
        return $industryData['business_categories'] ?? [];
    }

    /**
     * Get the default industry (fallback for existing orgs).
     */
    public function getDefaultIndustry(): string
    {
        return config('businessos.default_industry', 'retail');
    }

    /**
     * Get the default business category.
     */
    public function getDefaultBusinessCategory(): string
    {
        return 'general';
    }

    /**
     * Get industry label.
     */
    public function getLabel(string $industry): string
    {
        return $this->getIndustry($industry)['label'] ?? $industry;
    }

    /**
     * Get industry icon.
     */
    public function getIcon(string $industry): string
    {
        return $this->getIndustry($industry)['icon'] ?? 'fa-building';
    }

    /**
     * Get industry color.
     */
    public function getColor(string $industry): string
    {
        return $this->getIndustry($industry)['color'] ?? '#6b7280';
    }

    /**
     * Check if industry feature is enabled.
     */
    public function isIndustryFeatureEnabled(): bool
    {
        return config('businessos.features.industry', false);
    }
}