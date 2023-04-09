<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/WaitAdmin
// | github:  https://github.com/topwait/waitadmin
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\frontend\cache;

use think\facade\Cache;

/**
 * 首次登录强制绑定手机缓存类
 * 该缓存记录了登录的信息
 */
class WebEnrollCache
{
    private static int $ttl = 910;
    private static string $prefix = 'login:sign:';

    /**
     * 读取
     *
     * @param string $key
     * @return array
     */
    public static function get(string $key): array
    {
        $wor = self::$prefix . $key;
        $val = Cache::get($wor);
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
        Cache::set($key, $value, self::$ttl);
    }

    /**
     * 删除
     *
     * @param string $key
     * @author zero
     */
    public static function delete(string $key): void
    {
        $key = self::$prefix . $key;
        Cache::delete($key);
    }
}