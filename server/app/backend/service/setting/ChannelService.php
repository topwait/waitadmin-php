<?php

namespace app\backend\service\setting;

use app\common\basics\Service;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;
use JetBrains\PhpStorm\ArrayShape;

/**
 * 渠道配置服务类
 */
class ChannelService extends Service
{
    /**
     * 渠道配置信息
     *
     * @return array[]
     * @author windy
     */
    #[ArrayShape(['wx' => "array", 'oa' => "array", 'op' => "array"])]
    public static function detail(): array
    {
        $wxChannel = ConfigUtils::get('wx_channel');
        $oaChannel = ConfigUtils::get('oa_channel');
        $opChannel = ConfigUtils::get('op_channel');

        return [
            'wx' => [
                'name'        => $wxChannel['name']        ?? '',
                'app_id'      => $wxChannel['app_id']      ?? '',
                'app_secret'  => $wxChannel['app_secret']  ?? '',
                'original_id' => $wxChannel['original_id'] ?? '',
                'qr_code'     => UrlUtils::toAbsoluteUrl($wxChannel['qr_code'] ?? ''),
            ],
            'oa' => [
                'name'       => $oaChannel['name']         ?? '',
                'app_id'      => $oaChannel['app_id']      ?? '',
                'app_secret'  => $oaChannel['app_secret']  ?? '',
                'original_id' => $oaChannel['original_id'] ?? '',
                'qr_code'     => UrlUtils::toAbsoluteUrl($oaChannel['qr_code'] ?? ''),
            ],
            'op' => [
                'app_id'      => $opChannel['app_id']     ?? '',
                'app_secret'  => $opChannel['app_secret'] ?? '',
            ]
        ];
    }

    /**
     * 渠道配置保存
     *
     * @param array $post
     * @author windy
     */
    public static function save(array $post): void
    {
        ConfigUtils::setItem('wx_channel', [
            'name'        => $post['wx_name']??'',
            'app_id'      => $post['wx_app_id']??'',
            'app_secret'  => $post['wx_app_secret']??'',
            'original_id' => $post['wx_original_id']??'',
            'qr_code'     => UrlUtils::toRelativeUrl($post['wx_qr_code']??''),
        ]);

        ConfigUtils::setItem('oa_channel', [
            'name'        => $post['oa_name']??'',
            'app_id'      => $post['oa_app_id']??'',
            'app_secret'  => $post['oa_app_secret']??'',
            'original_id' => $post['oa_original_id']??'',
            'qr_code'     => UrlUtils::toRelativeUrl($post['oa_qr_code']??''),
        ]);

        ConfigUtils::setItem('op_channel', [
            'app_id'      => $post['op_appId']??'',
            'app_secret'  => $post['op_app_secret']??'',
        ]);
    }
}