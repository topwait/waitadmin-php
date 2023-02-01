<?php

namespace app\api\service;

use app\common\basics\Service;
use app\common\model\user\User;

class UsersService extends Service
{
    /**
     * 个人中心
     *
     * @param int $id
     * @return array
     * @author windy
     */
    public static function center(int $id): array
    {
        $modelUser = new User();
        return $modelUser
            ->field(['id,sn,account,nickname,avatar,mobile,email,sex'])
            ->where(['id'=>$id])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();
    }
}