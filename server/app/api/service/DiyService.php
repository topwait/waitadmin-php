<?php

namespace app\api\service;

use app\common\basics\Service;
use app\common\utils\AjaxUtils;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;

/**
 * 装修服务类
 */
class DiyService extends Service
{
    public function index()
    {

    }

    public function tie()
    {

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