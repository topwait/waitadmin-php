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
     * @author zero
     */
    public static function getWxConfig(): array
    {
        $wxConfig = ConfigUtils::get('wx_channel');
        $detail['config'] = [
            'app_id' => $wxConfig['app_id'] ?? '',
            'secret' => $wxConfig['app_secret'] ?? '',
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
     * @author zero
     */
    public static function getOaConfig(): array
    {
        $oaConfig = ConfigUtils::get('oa_channel');
        $detail['config'] = [
            'app_id'  => $oaConfig['app_id']     ?? '',
            'secret'  => $oaConfig['app_secret'] ?? '',
            'token'   => $oaConfig['token']      ?? '',
            'aes_key' => $oaConfig['aes_key']    ?? '',
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
     * @author zero
     */
    public static function getOpConfig(): array
    {
        $opConfig = ConfigUtils::get('op_channel');
        $detail['config'] = [
            'app_id'  => $opConfig['app_id']??'',
            'secret'  => $opConfig['app_secret']??'',
            'http'    => [
                'timeout' => 5.0,
                'throw'   => true,
                'retry'   => true
            ],
        ];

        return $detail['config'];
    }
}