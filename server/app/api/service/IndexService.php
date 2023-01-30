<?php

namespace app\api\service;

use app\common\basics\Service;
use app\common\utils\ConfigUtils;

/**
 * 主页服务类
 */
class IndexService extends Service
{
    /**
     * 全局配置
     *
     * @return array
     * @author windy
     */
    public static function config(): array
    {
        // 登录配置
        $login = ConfigUtils::get('login');
        $detail['login'] = [
            'force_mobile' => intval($login['force_mobile']??0),
            'login_modes'  => json_decode($login['login_modes']??'[]', true),
            'login_other'  => json_decode($login['login_modes']??'[]', true),
        ];

        return $detail;
    }
}