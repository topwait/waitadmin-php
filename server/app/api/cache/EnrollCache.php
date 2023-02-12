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
     * @return mixed
     */
    public static function get(string $key): mixed
    {
        $ktt = self::$prefix . $key;
        $val = cache($ktt);
        if ($val) {
            self::delete($key);
            return json_decode($val, true);
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