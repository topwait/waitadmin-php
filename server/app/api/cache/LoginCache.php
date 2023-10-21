<?php
// +----------------------------------------------------------------------
// | WaitChat智能聊天系统
// +----------------------------------------------------------------------
// | 这不是一个自由软件,您只能在不用于商业目的的前提下对程序代码进行修改和使用。
// | 任何企业和个人不允许对程序代码以任何形式任何目的再发布,商业使用请获取授权。
// | 获取商业授权后,允许对程序进行二次开发修改,并且可进行商业运营使用。
// +----------------------------------------------------------------------
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com | 2273716447@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\api\cache;

use think\facade\Cache;

/**
 * Token登录缓存类
 */
class LoginCache
{
    private static int $ttl = 7200;
    private static string $prefix = 'login:token:';

    /**
     * 读取
     *
     * @param int $terminal
     * @param string $token
     * @return int
     * @author zero
     */
    public static function get(int $terminal, string $token): int
    {
        $cacheKey = self::$prefix.$terminal.':'.$token;
        $cacheVal = Cache::get($cacheKey, '');
        if (!$cacheVal) {
            return 0;
        }

        $value = explode(':', $cacheVal);
        $userId = intval($value[0]??0);
        $time   = intval($value[1]??0);

        // 续签令牌 (低于30分钟)
        $expire = ($time + self::$ttl) - (60 * 30);
        if ($time && time() >= $expire) {
            self::set($userId, $terminal, $token);
        }

        return $userId;
    }

    /**
     * 设置
     *
     * @param int $userId
     * @param int $terminal
     * @param string $token
     * @author zero
     */
    public static function set(int $userId, int $terminal, string $token): void
    {
        $cacheKey = self::$prefix.$terminal.':'.$token;
        $cacheVal = $userId . ':' . time();
        Cache::set($cacheKey, $cacheVal, self::$ttl);
    }

    /**
     * 删除
     *
     * @param int $terminal
     * @param string $token
     * @author zero
     */
    public static function del(int $terminal, string $token)
    {
        $cacheKey = self::$prefix.$terminal.':'.$token;
        Cache::delete($cacheKey);
    }
}