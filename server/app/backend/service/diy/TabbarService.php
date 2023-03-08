<?php

namespace app\backend\service\diy;

use app\common\basics\Service;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;
use JetBrains\PhpStorm\ArrayShape;

class TabbarService extends Service
{
    /**
     * 底部导航详情
     *
     * @return array
     * @author windy
     */
    #[ArrayShape(['style' => "array", 'list' => "array"])]
    public static function detail(): array
    {
        (array) $style = ConfigUtils::get('diy', 'tab_bar_style', [
            'selectedColor'   => '#000000',
            'unselectedColor' => '#000000'
        ]);

        (array) $list  = ConfigUtils::get('diy', 'tab_bar_list', []);
        foreach ($list as &$item) {
            $item['iconPath'] = UrlUtils::toAbsoluteUrl($item['iconPath']);
            $item['selectedIconPath'] = UrlUtils::toAbsoluteUrl($item['selectedIconPath']);
        }

        return ['style'=>$style, 'list'=>$list];
    }

    /**
     * 底部导航保存
     *
     * @param array $post
     * @author windy
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

        ConfigUtils::setItem('diy', [
            'tab_bar_list'  => json_encode($list),
            'tab_bar_style' => json_encode([
                'selectedColor'   => $post['style']['selectedColor']??'',
                'unselectedColor' => $post['style']['unselectedColor']??''
            ])
        ]);
    }
}