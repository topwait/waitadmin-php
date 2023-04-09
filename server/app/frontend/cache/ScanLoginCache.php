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
 * 微信扫码登录缓存类
 */
class ScanLoginCache
{
    private static int $ttl = 600;
    private static string $prefix = 'login:scan:';

    /**
     * 读取
     *
     * @param string $state
     * @return string
     * @author zero
     */
    public static function get(string $state): string
    {
        $value = Cache::get(self::$prefix . $state);
        self::delete($value);
        return $value;
    }

    /**
     * 设置
     *
     * @param string $state
     * @author zero
     */
    public static function set(string $state): void
    {
        Cache::set(self::$prefix . $state, $state, self::$ttl);
    }

    /**
     * 删除
     *
     * @param string $state
     * @author zero
     */
    public static function delete(string $state): void
    {
        Cache::delete(self::$prefix . $state);
    }
}