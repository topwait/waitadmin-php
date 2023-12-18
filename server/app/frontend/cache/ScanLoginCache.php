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
    private static int $ttl = 120;
    private static string $prefix = 'login:scan:';

    public static int $ING  = 0;
    public static int $OK   = 1;
    public static int $FAIL = 2;

    /**
     * 读取
     *
     * @param string $state
     * @return array
     * @author zero
     */
    public static function get(string $state): array
    {
        return Cache::get(self::$prefix . $state, []);
    }

    /**
     * 设置
     *
     * @param string $state (令牌)
     * @param array $data   (['status'=>0=进行中,1=成功,2=失败, 'openid'=>''])
     * @author zero
     */
    public static function set(string $state, array $data): void
    {
        Cache::set(self::$prefix . $state, $data, self::$ttl);
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