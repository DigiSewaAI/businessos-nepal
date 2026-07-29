<?php

if (! function_exists('branding')) {
    /**
     * Get a branding value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function branding(string $key, $default = null)
    {
        return app(\App\Services\BrandingService::class)->get($key, $default);
    }
}