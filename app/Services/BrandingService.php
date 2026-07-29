<?php

namespace App\Services;

use Illuminate\Support\Arr;

class BrandingService
{
    /**
     * Get a branding value by key.
     * If a market override exists (based on current country), it takes precedence.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        // Determine current market (set via middleware in V2; for now use default)
        $market = config('branding.default_market', 'global');

        // Check if there is a market override for this key
        $override = config("branding.markets.{$market}.{$key}");
        if ($override !== null) {
            return $override;
        }

        // Fallback to global value
        return config("branding.{$key}", $default);
    }

    /**
     * Magic method for quick access: BrandingService::hero_badge()
     */
    public function __call($method, $arguments)
    {
        // Convert method name to snake_case key
        $key = snake_case($method);
        return $this->get($key, $arguments[0] ?? null);
    }
}