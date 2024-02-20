<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
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
    public static function set(string|int $key, array $value)
    {
        $k = self::$prefix . $key;
        Cache::set($k, $value);
    }
}