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

namespace app\common\utils;


/**
 * Url工具
 *
 * Class UrlUtils
 * @package app\common\utils
 */
class UrlUtils
{
    /**
     * 转绝对路径
     * 示例:
     *  转换前: image/20220819/ad06320.jpeg
     *  转换后: http://www.waitadmin.cn/storage/image/20220819/ad06320.jpeg
     *
     * @param string $url (相对路径)
     * @return string     (绝对路径)
     */
    public static function toAbsoluteUrl(string $url = ''): string
    {
        if ($url === '' || $url === '/') {
            return $url;
        }

        if (str_starts_with($url, 'http:'.'//') || str_starts_with($url, 'https://')) {
            return $url;
        }

        if (!str_starts_with($url, '/')) {
            $url = '/' . $url;
        }

        if (str_starts_with($url, '/static') || str_starts_with($url, '/temporary')) {
            return request()->domain() . $url;
        }

        $engine = ConfigUtils::get('storage', 'default', 'local');
        if ($engine === 'local') {
            return request()->domain() . $url;
        } else {
            $config = ConfigUtils::get('storage', $engine, []);
            $domain = $config['domain'] ?? '';
            return rtrim($domain, '/') . $url;
        }
    }

    /**
     * 转相对路径
     * 示例:
     *  转换前: http://www.waitadmin.cn/storage/image/20220819/ad06320.jpeg
     *  转换后: image/20220819/ad06320.jpeg
     *
     * @param string $url (绝对路径)
     * @return string     (相对路径)
     */
    public static function toRelativeUrl(string $url): string
    {
        if (str_starts_with($url, 'http:'.'//') || str_starts_with($url, 'https://')) {
            $url = str_replace('http:'.'//', '', $url);
            $url = str_replace('https://', '', $url);
            $arr = explode('/', $url);
            array_shift($arr);
            return implode('/', $arr);
        }
        return $url;
    }

    /**
     * 转本地根路径
     * 示例:
     *  转换前: storage/image/20220819/ad06320.jpeg
     *  转换后: /www/server/wait/public/storage/image/20220819/ad06320.jpeg
     *
     * @param string $url (相对路径)
     * @return string
     * @author windy
     */
    public static function toRoot(string $url): string
    {
        if (str_starts_with($url, 'http:'.'//') || str_starts_with($url, 'https://')) {
            $url = self::toRelativeUrl($url);
        }

        $rootPath = public_path() . $url;
        $rootPath = str_replace('\\', '/', $rootPath) ;
        return strval($rootPath);
    }
}