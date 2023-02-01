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
        $loginOther = array_map(function ($val) {return intval($val);}, $login['loginOther']??[]);
        if (in_array('1', $login['loginModes']??[])) $loginModes[] = ['alias'=>'account', 'name'=>'账号登录'];
        if (in_array('2', $login['loginModes']??[])) $loginModes[] = ['alias'=>'mobile', 'name'=>'免密登录'];
        $detail['login'] = [
            'forceMobile' => intval($login['forceMobile']??0),
            'loginModes'  => $loginModes??[],
            'loginOther'  => $loginOther??[],
        ];

        return $detail;
    }
}