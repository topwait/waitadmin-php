<?php

namespace app\api\cache;

class EnrollCache
{
    private static int $ttl = 900;
    private static string $prefix = 'login:sign:';

    /**
     * 获取
     *
     * @param string $key
     * @return array
     */
    public static function get(string $key): array
    {
        $wor = self::$prefix . $key;
        $val = cache($wor);
        if ($val) {
            self::delete($key);
            return $val;
        }
        return [];
    }

    /**
     * 设置
     *
     * @param string $key
     * @param array $value
     */
    public static function set(string $key, array $value): void
    {
        $key = self::$prefix . $key;
        cache($key, $value, self::$ttl);
    }

    /**
     * 删除
     *
     * @param string $key
     * @author windy
     */
    public static function delete(string $key)
    {
        $key = self::$prefix . $key;
        cache($key, null);
    }
}