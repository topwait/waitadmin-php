<?php

namespace app\api\widgets;

class TokenWidget
{
    /**
     * 会话登录
     */
    public static function login()
    {
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