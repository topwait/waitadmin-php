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
    #[ArrayShape(['wx' => "array", 'oa' => "array", 'op' => "array", 'h5' => "array"])]
    public static function detail(): array
    {
        $wxChannel = ConfigUtils::get('wx_channel');
        $oaChannel = ConfigUtils::get('oa_channel');
        $opChannel = ConfigUtils::get('op_channel');
        $h5Channel = ConfigUtils::get('h5_channel');

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
            ],
            'h5' => [
                'status'    => intval($h5Channel['status'] ?? 0),
                'close_url' => $h5Channel['close_url'] ?? '',
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
        ConfigUtils::set('wx_channel', 'name', $post['wx_name']??'', '小程序名称');
        ConfigUtils::set('wx_channel', 'app_id', $post['wx_app_id']??'', 'AppID');
        ConfigUtils::set('wx_channel', 'app_secret', $post['wx_app_secret']??'', 'AppSecret');
        ConfigUtils::set('wx_channel', 'original_id', $post['wx_original_id']??'', '原始ID');
        ConfigUtils::set('wx_channel', 'qr_code', UrlUtils::toRelativeUrl($post['wx_qr_code']??''), '二维码');

        ConfigUtils::set('oa_channel', 'name', $post['oa_name']??'', '公众号名称');
        ConfigUtils::set('oa_channel', 'app_id', $post['oa_app_id']??'', 'AppID');
        ConfigUtils::set('oa_channel', 'app_secret', $post['oa_app_secret']??'', 'AppSecret');
        ConfigUtils::set('oa_channel', 'original_id', $post['oa_original_id']??'', '原始ID');
        ConfigUtils::set('oa_channel', 'qr_code', UrlUtils::toRelativeUrl($post['oa_qr_code']??''), '二维码');

        ConfigUtils::set('op_channel', 'app_id', $post['op_appId']??'', 'AppID');
        ConfigUtils::set('op_channel', 'app_secret', $post['op_app_secret']??'', 'AppSecret');

        ConfigUtils::set('h5_channel', 'status', $post['h5_status']??0, '渠道状态');
        ConfigUtils::set('h5_channel', 'close_url', $post['h5_close_url']??'', '关闭页面');
    }
}