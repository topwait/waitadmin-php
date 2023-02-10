<?php

namespace app\api\service;

use app\common\basics\Service;
use app\common\model\user\User;
use app\common\model\user\UserAuth;

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

    /**
     * 用户信息
     *
     * @param int $id
     * @return array
     */
    public static function info(int $id): array
    {
        $modelUser = new User();
        return $modelUser
            ->field(['id,sn,account,nickname,avatar,mobile,email,sex'])
            ->where(['id'=>$id])
            ->where(['is_delete'=>0])
            ->withAttr(['isWeiChat' => function() use ($id) {
                $modelUserAuth = new UserAuth();
                return !$modelUserAuth->field(['id'])
                    ->where(['user_id'=>$id])
                    ->where(['terminal'=>1])
                    ->findOrEmpty()
                    ->isEmpty();
            }])
            ->append(['isWeiChat'])
            ->findOrEmpty()
            ->toArray();
    }
}