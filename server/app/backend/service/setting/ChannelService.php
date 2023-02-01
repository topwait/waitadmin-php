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
        $wxChannel = ConfigUtils::get('wxChannel');
        $oaChannel = ConfigUtils::get('oaChannel');
        $opChannel = ConfigUtils::get('opChannel');
        $h5Channel = ConfigUtils::get('h5Channel');

        return [
            'wx' => [
                'name'       => $wxChannel['name'] ?? '',
                'originalId' => $wxChannel['originalId'] ?? '',
                'appId'      => $wxChannel['appId'] ?? '',
                'appSecret'  => $wxChannel['appSecret'] ?? '',
                'qrCode'     => UrlUtils::toAbsoluteUrl($wxChannel['qrCode'] ?? ''),
            ],
            'oa' => [
                'name'       => $oaChannel['name'] ?? '',
                'originalId' => $oaChannel['originalId'] ?? '',
                'appId'      => $oaChannel['appId'] ?? '',
                'appSecret'  => $oaChannel['appSecret'] ?? '',
                'qrCode'     => UrlUtils::toAbsoluteUrl($oaChannel['qrCode'] ?? ''),
            ],
            'op' => [
                'appId'      => $opChannel['appId'] ?? '',
                'appSecret'  => $opChannel['appSecret'] ?? '',
            ],
            'h5' => [
                'status'   => intval($h5Channel['status'] ?? 0),
                'closeUrl' => $h5Channel['closeUrl'] ?? '',
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
        ConfigUtils::set('wxChannel', 'name', $post['wxName']??'', '小程序名称');
        ConfigUtils::set('wxChannel', 'appId', $post['wxAppId']??'', 'AppID');
        ConfigUtils::set('wxChannel', 'appSecret', $post['wxAppSecret']??'', 'AppSecret');
        ConfigUtils::set('wxChannel', 'originalId', $post['wxOriginalId']??'', '原始ID');
        ConfigUtils::set('wxChannel', 'qrCode', UrlUtils::toRelativeUrl($post['wxQrCode']??''), '二维码');

        ConfigUtils::set('oaChannel', 'name', $post['oaName']??'', '公众号名称');
        ConfigUtils::set('oaChannel', 'appId', $post['oaAppId']??'', 'AppID');
        ConfigUtils::set('oaChannel', 'appSecret', $post['oaAppSecret']??'', 'AppSecret');
        ConfigUtils::set('oaChannel', 'originalId', $post['oaOriginalId']??'', '原始ID');
        ConfigUtils::set('oaChannel', 'qrCode', UrlUtils::toRelativeUrl($post['oaQrCode']??''), '二维码');

        ConfigUtils::set('opChannel', 'appId', $post['opAppId']??'', 'AppID');
        ConfigUtils::set('opChannel', 'appSecret', $post['opAppSecret']??'', 'AppSecret');

        ConfigUtils::set('h5Channel', 'status', $post['h5Status']??0, '渠道状态');
        ConfigUtils::set('h5Channel', 'closeUrl', $post['h5CloseUrl']??'', '关闭页面');
    }
}