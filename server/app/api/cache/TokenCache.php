<?php

namespace app\api\cache;

use think\facade\Cache;

/**
 * Token缓存类
 */
class TokenCache
{
    private static int $ttl = 7200;
    private static string $prefix = 'login:token:';

    public static function get(int $terminal, string $token): int
    {
        $cacheKey = self::$prefix.$terminal.':'.$token;
        return intval(Cache::get($cacheKey, 0));
    }

    public static function set(int $userId, int $terminal, string $token): void
    {
        $cacheKey = self::$prefix.$terminal.':'.$token;
        Cache::set($cacheKey, $userId, self::$ttl);
    }
}