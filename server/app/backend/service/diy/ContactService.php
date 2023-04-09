<?php

namespace app\backend\service\diy;

use app\common\basics\Service;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;

/**
 * 客服装修服务类
 */
class ContactService extends Service
{
    /**
     * 客服装修详情
     *
     * @return array
     * @author zero
     */
    public static function detail(): array
    {
        $detail = ConfigUtils::get('diy', 'contact', [
            'title'    => '',
            'datetime' => '',
            'mobile'   => '',
            'qq'       => '',
            'image'    => ''
        ]);

        $detail['image'] = UrlUtils::toAbsoluteUrl($detail['image']??'');
        return (array) $detail;
    }

    /**
     * 客服装修保存
     *
     * @param array $post
     * @author zero
     */
    public static function save(array $post)
    {
        ConfigUtils::set('diy', 'contact', [
            'title'    => $post['title']??'',
            'datetime' => $post['datetime']??'',
            'mobile'   => $post['mobile']??'',
            'qq'       => $post['qq']??'',
            'image'    => UrlUtils::toRelativeUrl($post['image']??'')
        ]);
    }
}