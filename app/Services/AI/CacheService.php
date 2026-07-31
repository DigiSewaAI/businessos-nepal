<?php
namespace App\Services\AI;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    public function remember(string $key, $callback, int $ttl = 3600)
    {
        return Cache::remember("ai_{$key}", $ttl, $callback);
    }

    public function rememberUser(string $key, $callback, int $ttl = 300)
    {
        $userId = auth()->id();
        return Cache::remember("ai_user_{$userId}_{$key}", $ttl, $callback);
    }

    public function forget(string $key)
    {
        Cache::forget("ai_{$key}");
    }

    public function forgetUser(string $key)
    {
        $userId = auth()->id();
        Cache::forget("ai_user_{$userId}_{$key}");
    }

    public function getStats(): array
    {
        return [
            'hit_count' => Cache::get('ai_cache_hits', 0),
            'miss_count' => Cache::get('ai_cache_misses', 0),
        ];
    }
}