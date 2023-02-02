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
        $loginOther = array_map(function ($val) {return intval($val);}, $login['login_other']??[]);
        if (in_array('1', $login['login_modes']??[])) $loginModes[] = ['alias'=>'account', 'name'=>'账号登录'];
        if (in_array('2', $login['login_modes']??[])) $loginModes[] = ['alias'=>'mobile', 'name'=>'免密登录'];
        $detail['login'] = [
            'is_agreement' => intval($login['is_agreement']??0),
            'force_mobile' => intval($login['force_mobile']??0),
            'login_modes'  => $loginModes??[],
            'login_other'  => $loginOther??[],
        ];

        return $detail;
    }
}