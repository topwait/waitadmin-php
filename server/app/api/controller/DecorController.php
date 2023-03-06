<?php

namespace app\api\controller;

/**
 * 装饰管理
 */
class DecorController
{
    /**
     * 首页页面
     */
    public function index()
    {

    }

    /**
     * 底部导航
     */
    public function tabBar(): array
    {
        return [
            [
                'text'       =>  '首页',
                'link'       => '/pages/index/index',
                'iconPath'   => "https://cdn.uviewui.com/uview/common/min_button.png",
                'selectedIconPath' => "https://cdn.uviewui.com/uview/common/min_button_select.png"
            ],
            [
                'text'       =>  '资讯',
                'link'       => '/pages/index/index',
                'iconPath'   => "https://cdn.uviewui.com/uview/common/min_button.png",
                'selectedIconPath' => "https://cdn.uviewui.com/uview/common/min_button_select.png"
            ],
            [
                'text'       =>  '我的',
                'link'       => '/pages/index/index',
                'iconPath'   => "https://cdn.uviewui.com/uview/common/min_button.png",
                'selectedIconPath' => "https://cdn.uviewui.com/uview/common/min_button_select.png"
            ]
        ];
    }
}