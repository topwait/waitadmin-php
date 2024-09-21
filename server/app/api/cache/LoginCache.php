<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/waitadmin-php
// | github:  https://github.com/topwait/waitadmin-php
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
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
    public static function delete(int $terminal, string $token): void
    {
        $cacheKey = self::$prefix.$terminal.':'.$token;
        Cache::delete($cacheKey);
    }
}