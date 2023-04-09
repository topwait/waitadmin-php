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

namespace app\api\service;

use app\common\basics\Service;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;
use JetBrains\PhpStorm\ArrayShape;

/**
 * 装修服务类
 */
class DiyService extends Service
{
    /**
     * 首页页面装修
     *
     * @return array
     * @author zero
     */
    #[ArrayShape(['bgHead' => "string", 'banner' => "array[]", 'nav' => "array[]"])]
    public static function index(): array
    {
        // 此处只是临时使用的数据,以后可接入diy功能
        return [
            'bgHead' => UrlUtils::toAbsoluteUrl('/static/common/init/bgHead.png'),
            'banner' => [
                ['image' => UrlUtils::toAbsoluteUrl('/static/common/init/banner01.jpg')],
                ['image' => UrlUtils::toAbsoluteUrl('/static/common/init/banner02.jpg')],
            ],
            'nav' => [
                [
                    'name'  =>'资讯中心',
                    'image' => UrlUtils::toAbsoluteUrl('/static/common/init/ic_article.png'),
                    'link'  => '/pages/article/list'
                ],
                [
                    'name'  =>'个人设置',
                    'image' => UrlUtils::toAbsoluteUrl('/static/common/init/ic_user.png'),
                    'link'  => '/pages/user/intro'
                ],
                [
                    'name'  => '联系我们',
                    'image' => UrlUtils::toAbsoluteUrl('/static/common/init/ic_contact.png'),
                    'link'  => '/pages/other/customer'
                ],
                [
                    'name'  => '关于我们',
                    'image' => UrlUtils::toAbsoluteUrl('/static/common/init/ic_about.png'),
                    'link'  => '/pages/other/about'
                ]
            ]
        ];
    }

    /**
     * 联系客服装修
     *
     * @return array
     * @author zero
     */
    #[ArrayShape(['title' => "string", 'datetime' => "string", 'mobile' => "string", 'qq' => "string", 'image' => "string"])]
    public static function tie(): array
    {
        $detail = ConfigUtils::get('diy', 'contact');
        return [
            'title'    => $detail['title']??'',
            'datetime' => $detail['datetime']??'',
            'mobile'   => $detail['mobile']??'',
            'qq'       => $detail['qq']??'',
            'image'    => UrlUtils::toAbsoluteUrl($detail['image']??'')
        ];
    }

    /**
     * 个人中心装修
     *
     * @return array
     * @author zero
     */
    public static function me(): array
    {
        $data = [];
        $detail = ConfigUtils::get('diy', 'person');
        foreach ($detail as $type => &$item) {
            $data[$type]['base'] = match ($type) {
                'adv' => [
                    'open' => intval($item['base']['open'] ?? '0')
                ],
                'service' => [
                    'layout' => $item['base']['layout'] ?? 'row',
                    'title'  => $item['base']['title'] ?? '',
                    'number' => $item['base']['number'] ?? 4,
                ]
            };

            foreach ($item['list'] as $value) {
                $data[$type]['list'][] = [
                    'image' => UrlUtils::toAbsoluteUrl($value['image']??''),
                    'name'  => $value['name']??'',
                    'link'  => $value['link']??'',
                ];
            }
        }

        return $data;
    }
}