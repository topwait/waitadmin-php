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

namespace app\backend\service\diy;

use app\common\basics\Service;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;

/**
 * 底部导航服务类
 */
class TabbarService extends Service
{
    /**
     * 底部导航详情
     *
     * @return array
     * @author zero
     */
    public static function detail(): array
    {
        $config = ConfigUtils::get('diy', 'tabbar', []);
        $style = [
            'selectedColor'   => $config['style']['selectedColor'] ?? '#2979ff',
            'unselectedColor' => $config['style']['unselectedColor'] ?? '##333333'
        ];

        $list = [];
        foreach ($config['list']??[] as $item) {
            $item['iconPath'] = UrlUtils::toAbsoluteUrl($item['iconPath']);
            $item['selectedIconPath'] = UrlUtils::toAbsoluteUrl($item['selectedIconPath']);
            $list[] = $item;
        }

        return ['style'=>$style, 'list'=>$list] ?? [];
    }

    /**
     * 底部导航保存
     *
     * @param array $post
     * @author zero
     */
    public static function save(array $post): void
    {
        $list = [];
        foreach ($post['list'] as $item) {
            $list[] = [
                'text' => $item['text'],
                'iconPath' => UrlUtils::toRelativeUrl($item['iconPath']),
                'selectedIconPath' => UrlUtils::toRelativeUrl($item['selectedIconPath']),
            ];
        }

        ConfigUtils::set('diy', 'tabbar', [
            'style' => [
                'selectedColor'   => $post['style']['selectedColor']??'',
                'unselectedColor' => $post['style']['unselectedColor']??''
            ],
            'list' => $list
        ]);
    }
}