<?php

namespace app\api\widgets;

use think\facade\Cache;

class TokenWidget
{
    /**
     * 会话登录
     */
    public static function login()
    {
        $cache = Cache::get('login:session:1');
        if (!$cache) {
            $build = [
                'id' => 'admin:login:session:1',
                'createTime' => time(),
                'updateTime' => time(),
                'tokenLists' => [
                    [
                        'value'         => 'OwCAnCJbE43siVV4TliYJy',
                        'device'        => 1,
                        'lastOpTime'    => time(),
                        'lastIpAddress' => request()->ip(),
                        'lastUaBrowser' => request()->header('User-Agent')
                    ]
                ]
            ];
        } else {
            $cacheArray = json_decode($cache, true);
            $cacheArray['updateTime'] = time();
            $tokenLists = [];
            foreach ($cacheArray['tokenLists'] as $item) {
                if ($item['value'] = '') {
                    $item['lastOpTime'] = time();
                    $item['lastIpAddress'] = request()->ip();
                    $item['lastUaBrowser'] = request()->header('User-Agent');
                }
            }
        }

        $session = [
            'id' => 'admin:login:session:1',
            'createTime' => time(),
            'updateTime' => time(),
            'tokenLists' => [
                [
                    'value'         => 'OwCAnCJbE43siVV4TliYJy',
                    'device'        => 1,
                    'lastOpTime'    => time(),
                    'lastUaBrowser' => time(),
                    'lastIpAddress' => time()
                ]
            ]
        ];
    }

    /**
     * 注销登录
     */
    public static function logout()
    {

    }

    /**
     * 踢人下线
     */
    public static function kickOut()
    {

    }
}