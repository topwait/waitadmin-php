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

namespace app\backend\service\setting;


use app\common\basics\Service;
use app\common\utils\ConfigUtils;
use app\common\utils\FileUtils;
use app\common\utils\UrlUtils;

/**
 * 水印配置服务类
 *
 * Class WatermarkService
 * @package app\admin\service\setting
 */
class WatermarkService extends Service
{
    /**
     * 水印配置详情
     *
     * @return array
     * @author windy
     */
    public static function detail(): array
    {
        $config = ConfigUtils::get('watermark');
        return [
            'status'   => intval($config['status'] ?? 0),
            'type'     => $config['type']  ?? 'image',
            'icon'     => UrlUtils::toAbsoluteUrl($config['icon'] ?? ''),
            'fonts'    => $config['fonts'] ?? '',
            'color'    => $config['color'] ?? '',
            'size'     => intval($config['size'] ?? 20),
            'offset'   => intval($config['offset'] ?? 0),
            'angle'    => intval($config['angle'] ?? 0),
            'alpha'    => intval($config['alpha'] ?? 0),
            'position' => intval($config['position'] ?? 1)
        ];
    }

    /**
     * 水印配置保存
     *
     * @param array $post
     * @author windy
     */
    public static function save(array $post): void
    {
        ConfigUtils::set('watermark', 'status', $post['status']??0, '水印功能状态');
        ConfigUtils::set('watermark', 'type', $post['type']??'image', '水印文件类型');
        ConfigUtils::set('watermark', 'fonts', $post['fonts']??'', '水印字体文字');
        ConfigUtils::set('watermark', 'color', $post['color']??'', '水印字体颜色');
        ConfigUtils::set('watermark', 'size', $post['size']??20, '水印字体大小');
        ConfigUtils::set('watermark', 'offset', $post['offset']??0, '水印字体偏移');
        ConfigUtils::set('watermark', 'angle', $post['angle']??0, '水印字体倾斜');
        ConfigUtils::set('watermark', 'alpha', $post['alpha']??100, '水印图透明度');
        ConfigUtils::set('watermark', 'position', $post['position']??1, '水印所在位置');

        $icon = ($post['icon']??'') ? 'static/common/watermark.png' : '';
        if ($icon) {
            $source = public_path() . UrlUtils::toRelativeUrl($post['icon']);
            $target = public_path() . $icon;
            FileUtils::move($source, $target);
        }
        ConfigUtils::set('watermark', 'icon', $icon, '水印图片文件');
    }
}