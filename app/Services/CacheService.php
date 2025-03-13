<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use JsonException;

class CacheService
{
    /**
     * Cache data using Laravel's Cache facade.
     * 
     * @param string $key
     * @param int $ttl
     * @param callable $callback
     * @return mixed
     */
    public static function remember(string $key, int $ttl, callable $callback): mixed
    {
        Log::info("Caching key: $key for $ttl seconds");
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Forget a cached item by key.
     * 
     * @param string $key
     * @return bool
     */
    public static function forget(string $key): bool
    {
        return Cache::forget($key);
    }

    /**
     * Flush all cached items.
     * 
     * @return bool
     */
    public static function flush(): bool
    {
        return Cache::flush();
    }

    /**
     * Cache data as JSON-encoded strings, similar to Redis hashes.
     * 
     * @param string $key
     * @param array $data
     * @param int $ttl
     * @return bool
     */
    public static function setHashData(string $key, array $data, int $ttl = 3600): bool
    {
        try {
            $jsonData = json_encode($data, JSON_THROW_ON_ERROR);
            return Cache::put($key, $jsonData, $ttl);
        } catch (JsonException $e) {
            Log::error("JSON encoding error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieve JSON-encoded data from the cache.
     * 
     * @param string $key
     * @return array|null
     */
    public static function getHashData(string $key): ?array
    {
        try {
            $data = Cache::get($key);
            if ($data === null) {
                return null;
            }
            return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            Log::error("JSON decoding error: " . $e->getMessage());
            return null;
        }
    }
}

