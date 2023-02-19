<?php

namespace app\api\service;

use app\common\basics\Service;
use app\common\exception\OperateException;
use app\common\model\user\User;
use app\common\model\user\UserAuth;
use app\common\utils\UrlUtils;

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
            ->field(['id,sn,account,nickname,avatar,mobile,email,gender'])
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
            ->field(['id,sn,account,nickname,avatar,mobile,email,gender'])
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

    /**
     * 用户编辑
     *
     * @param array $post
     * @param int $userId
     * @throws OperateException
     */
    public static function edit(array $post, int $userId)
    {
        $scene = $post['scene'];
        $value = $post['value'];

        $modelUser = new User();
        switch ($scene) {
            case 'account':
                $user = $modelUser->field(['id,account'])
                    ->where(['account'=>$value])
                    ->where(['is_delete'=>0])
                    ->findOrEmpty()
                    ->toArray();

                if ($user) {
                    throw new OperateException('该账号已被占用!');
                }

                User::update(['account'=>$value, 'update_time'=>time()], ['id'=>$userId]);
                break;
            case 'nickname':
                $user = $modelUser->field(['id,nickname'])
                    ->where(['nickname'=>$value])
                    ->where(['is_delete'=>0])
                    ->findOrEmpty()
                    ->toArray();

                if ($user) {
                    throw new OperateException('该昵称已被占用!');
                }

                User::update(['nickname'=>$value, 'update_time'=>time()], ['id'=>$userId]);
                break;
            case 'gender':
                User::update(['gender'=>$value, 'update_time'=>time()], ['id'=>$userId]);
                break;
            case 'avatar':
                $avatar = UrlUtils::toRelativeUrl($value);
                User::update(['avatar'=>$avatar, 'update_time'=>time()], ['id'=>$userId]);
                break;
        }
    }
}