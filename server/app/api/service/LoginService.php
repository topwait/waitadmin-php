<?php

namespace app\api\service;

use app\api\widgets\UserWidget;
use app\common\basics\Service;
use app\common\exception\OperateException;
use app\common\model\user\User;
use app\common\service\wechat\WeChatService;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

class LoginService extends Service
{
    /**
     * 注册账号
     *
     * @param array $post
     * @throws Exception
     * @author windy
     */
    public static function register(array $post)
    {
        $code     = $post['code'];
        $mobile   = $post['mobile'];
        $account  = $post['account'];
        $password = $post['password'];

        UserWidget::createUser([
            'mobile'   => $mobile,
            'account'  => $account,
            'password' => $password,
        ]);
    }

    /**
     * 账号登录
     *
     * @param $account  (账号)
     * @param $password (密码)
     * @return array
     * @throws OperateException
     */
    #[ArrayShape(['token' => "string"])]
    public static function accountLogin(string $account, string $password): array
    {
        $modelUser = new User();
        $userInfo = $modelUser
            ->field(['id,password,salt,is_disable'])
            ->where(['username'=>$account])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        if (!$userInfo) {
            throw new OperateException('账号不存在!');
        }

        $password = make_md5_str($password, $userInfo['salt']);
        if ($userInfo['password'] !== $password) {
            throw new OperateException('账号或密码错误!');
        }

        if ($userInfo['is_disable']) {
            throw new OperateException('账号已被禁用!');
        }

        $token = UserWidget::grantToken(1, 1);
        return ['token'=>$token];
    }

    /**
     * 短信登录
     *
     * @param string $mobile (手机号)
     * @param string $code   (验证码)
     * @return array
     * @throws OperateException
     * @author windy
     */
    #[ArrayShape(['token' => "string"])]
    public static function mobileLogin(string $mobile, string $code): array
    {
        $modelUser = new User();
        $userInfo = $modelUser
            ->field(['id,mobile,is_disable'])
            ->where(['mobile'=>$mobile])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        if (!$userInfo) {
            throw new OperateException('账号不存在!');
        }

        if ($userInfo['is_disable']) {
            throw new OperateException('账号已被禁用!');
        }

        $token = UserWidget::grantToken(1, 1);
        return ['token'=>$token];
    }

    /**
     * 微信登录
     *
     * @param string $code  (微信小程序编码)
     * @param int $terminal (客户端[1=微信小程序, 2=微信公众号, 3=H5, 4=PC, 5=安卓, 6=苹果])
     * @return array
     * @throws Exception
     */
    #[ArrayShape(['token' => "int"])]
    public static function wxLogin(string $code, int $terminal): array
    {
        $phoneArr = WeChatService::wxPhoneNumber($code);
        $response = WeChatService::wxJsCode2session($code);
        $response['terminal'] = $terminal;

        $userInfo = UserWidget::getUserAuthByResponse($response);
        if (empty($userInfo)) {
            $userId = UserWidget::createUser($response);
        } else {
            $userId = UserWidget::updateUser($response);
        }

        $token = UserWidget::grantToken(1, 1);
        return ['token'=>$token];
    }

    public static function oaLogin()
    {

    }

}