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

namespace app\common\cache;

use think\facade\Cache;

/**
 * 权限缓存类型
 */
class PermsCache
{
    private static string $prefix = 'admin:perms:';

    /**
     * 读取
     *
     * @param string|int $key
     * @return array
     * @author zero
     */
    public static function get(string|int $key): array
    {
        $k = self::$prefix . $key;
        return Cache::get($k);
    }

    /**
     * 设置
     *
     * @param string|int $key
     * @param array $value
     * @author zero
     */
    public static function set(string|int $key, array $value): void
    {
        $k = self::$prefix . $key;
        Cache::set($k, $value);
    }
}