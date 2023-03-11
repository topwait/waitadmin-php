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
     * @author windy
     */
    public static function index(): array
    {
        return [];
    }

    /**
     * 联系客服装修
     *
     * @return array
     * @author widy
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
     * @author windy
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