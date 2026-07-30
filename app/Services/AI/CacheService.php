<?php
namespace App\Services\AI;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    public function remember($key, $ttl, $callback)
    {
        $cacheKey = 'ai_' . md5($key);
        return Cache::remember($cacheKey, $ttl, $callback);
    }

    public function forget($key)
    {
        Cache::forget('ai_' . md5($key));
    }

    public function clearAll()
    {
        // Only clear AI cache keys
        $keys = Cache::get('ai_cache_keys', []);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget('ai_cache_keys');
    }
}