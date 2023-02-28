<?php

namespace app\api\service;

use app\common\basics\Service;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;

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
        $loginConfig = ConfigUtils::get('login');
        $loginOther  = array_map(function ($val) {return intval($val);}, $loginConfig['login_other']??[]);
        if (in_array('1', $loginConfig['login_modes']??[])) $loginModes[] = ['alias'=>'account', 'name'=>'账号登录'];
        if (in_array('2', $loginConfig['login_modes']??[])) $loginModes[] = ['alias'=>'mobile', 'name'=>'免密登录'];
        $detail['login'] = [
            'is_agreement' => intval($loginConfig['is_agreement']??0),
            'force_mobile' => intval($loginConfig['force_mobile']??0),
            'login_modes'  => $loginModes??[],
            'login_other'  => $loginOther??[],
        ];

        // H5配置
        $h5Config = ConfigUtils::get('h5');
        $detail['h5'] = [
            'title'     => $h5Config['title']??'',
            'logo'      => UrlUtils::toAbsoluteUrl($h5Config['logo']??''),
            'status'    => intval($h5Config['status']??0),
            'close_url' => strval($h5Config['close_url']??'')
        ];

        return $detail;
    }

    /**
     * 政策协议
     *
     * @return array
     * @author windy
     */
    public static function policy(): array
    {
        return [];
    }
}