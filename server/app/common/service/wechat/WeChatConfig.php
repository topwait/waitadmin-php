<?php

namespace app\common\service\wechat;

use app\common\utils\ConfigUtils;

/**
 * 微信参数配置类
 */
class WeChatConfig
{
    /**
     * 小程序配置
     *
     * @return array
     * @author windy
     */
    public static function getWxConfig(): array
    {
        $wxConfig = ConfigUtils::get('wxChannel');
        $detail['config'] = [
            'app_id' => $wxConfig['appId'] ?? '',
            'secret' => $wxConfig['appSecret'] ?? '',
            'http'   => [
                'timeout' => 5.0,
                'throw'   => true,
                'retry'   => true
            ],
        ];

        return $detail['config'];
    }

    /**
     * 公众号配置
     *
     * @return array
     * @author windy
     */
    public static function getOaConfig(): array
    {
        $oaConfig = ConfigUtils::get('oaChannel');
        $detail['config'] = [
            'app_id'  => $oaConfig['appId'] ?? '',
            'secret'  => $oaConfig['appSecret'] ?? '',
            'token'   => $oaConfig['token'] ?? '',
            'aes_key' => $oaConfig['aesKey'] ?? '',
            'http'    => [
                'timeout' => 5.0,
                'throw'   => true,
                'retry'   => true
            ],
        ];

        return $detail['config'];
    }

    /**
     * 开发平台配置
     *
     * @return array
     * @author windy
     */
    public static function getOpConfig(): array
    {
        $opConfig = ConfigUtils::get('opChannel');
        $detail['config'] = [
            'app_id'  => $opConfig['appId']??'',
            'secret'  => $opConfig['appSecret']??'',
            'http'    => [
                'timeout' => 5.0,
                'throw'   => true,
                'retry'   => true
            ],
        ];

        return $detail['config'];
    }
}