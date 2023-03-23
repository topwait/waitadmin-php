<?php

namespace app\frontend\service;

use app\common\basics\Service;
use app\common\exception\OperateException;
use app\common\model\user\User;
use app\common\utils\UrlUtils;

class LoginService extends Service
{
    /**
     * 注册账号
     *
     * @param array $post
     * @author windy
     */
    public static function register(array $post): void
    {
        $salt     = make_rand_char(5);
        $password = make_md5_str($post['password'], $salt);
        $user = User::create([
            'mobile'      => trim($post['mobile']),
            'nickname'    => trim($post['nickname']),
            'username'    => trim($post['username']),
            'password'    => trim($password),
            'salt'        => $salt,
            'last_login_ip'   => request()->ip(),
            'last_login_time' => time(),
            'create_time'     => time(),
            'update_time'     => time()
        ]);

        $userInfo = (new User())
            ->withoutField('is_delete,update_time,delete_time')
            ->where('id', $user['id'])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        $userInfo['avatar'] = UrlUtils::toAbsoluteUrl($userInfo['avatar']);
        session('userInfo', $userInfo);
    }

    /**
     * 登录账号
     *
     * @param array $post
     * @throws OperateException
     * @author windy
     */
    public static function login(array $post): void
    {
        $model = new User();
        $user = $model->withoutField('is_delete,update_time,delete_time')
            ->where('account|mobile|email', $post['account'])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        if (!$user) {
            throw new OperateException('账号或密码错误了!');
        }

        $password = make_md5_str($post['password'], $user['salt']);
        if ($password !== $user['password']) {
            throw new OperateException('账号或密码错误了!');
        }

        if ($user['is_disable']) {
            throw new OperateException('账号或密码错误了!');
        }

        User::update([
            'last_login_ip'   => request()->ip(),
            'last_login_time' => time()
        ], ['id'=>$user['id']]);


        $user['avatar'] = UrlUtils::toAbsoluteUrl($user['avatar']);
        session('userInfo', $user);
    }
}