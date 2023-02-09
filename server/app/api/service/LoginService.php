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
     * @param int $terminal
     * @return array
     * @throws OperateException
     * @throws Exception
     * @author windy
     */
    #[ArrayShape(['token' => "string"])]
    public static function register(array $post, int $terminal): array
    {
        // 接收参数
        $code     = $post['code'];
        $mobile   = $post['mobile'];
        $account  = $post['account'];
        $password = $post['password'];

        // 短信验证
        if ($code != '12345') {
            throw new OperateException('验证码错误');
        }

        // 创建账号
        $userId = UserWidget::createUser([
            'mobile'   => $mobile,
            'account'  => $account,
            'password' => $password,
            'terminal' => $terminal
        ]);

        // 登录账号
        $token = UserWidget::granToken($userId, $terminal);
        return ['token'=>$token];
    }

    /**
     * 重置密码
     *
     * @param array $post
     * @throws OperateException
     * @author windy
     */
    public static function forgetPwd(array $post)
    {
        // 接收参数
        $code     = $post['code'];
        $mobile   = $post['mobile'];
        $password = $post['password'];

        // 短信验证
        if ($code != '12345') {
            throw new OperateException('验证码错误');
        }

        // 查询账户
        $modelUser = new User();
        $userInfo = $modelUser->field(['id,mobile'])
            ->where(['mobile'=>trim($mobile)])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证账户
        if (!$userInfo) {
            throw new OperateException('账号不存在!');
        }

        // 设置密码
        $salt = make_rand_char(6);
        $password = make_md5_str($password, $salt);
        User::update([
            'salt'        => $salt,
            'password'    => $password,
            'update_time' => time()
        ], ['id'=>$userInfo['id']]);
    }

    /**
     * 账号登录
     *
     * @param $account  (账号)
     * @param $password (密码)
     * @param $terminal (渠道)
     * @return array
     * @throws OperateException
     */
    #[ArrayShape(['token' => "string"])]
    public static function accountLogin(string $account, string $password, int $terminal): array
    {
        // 查询账户
        $modelUser = new User();
        $userInfo = $modelUser
            ->field(['id,password,salt,is_disable'])
            ->where(['account'=>$account])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证账户
        if (!$userInfo) {
            throw new OperateException('账号不存在!');
        }

        // 验证密码
        $password = make_md5_str($password, $userInfo['salt']);
        if ($userInfo['password'] !== $password) {
            throw new OperateException('账号或密码错误!');
        }

        // 验证状态
        if ($userInfo['is_disable']) {
            throw new OperateException('账号已被禁用!');
        }

        // 登录账户
        $token = UserWidget::granToken(intval($userInfo['id']), $terminal);
        return ['token'=>$token];
    }

    /**
     * 短信登录
     *
     * @param string $mobile (手机号)
     * @param string $code (验证码)
     * @param int $terminal
     * @return array
     * @throws OperateException
     * @author windy
     */
    #[ArrayShape(['token' => "string"])]
    public static function mobileLogin(string $mobile, string $code, int $terminal): array
    {
        // 短信验证
        if ($code != '12345') {
            throw new OperateException('验证码错误');
        }

        // 查询账户
        $modelUser = new User();
        $userInfo = $modelUser
            ->field(['id,mobile,is_disable'])
            ->where(['mobile'=>$mobile])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证账户
        if (!$userInfo) {
            throw new OperateException('账号不存在!');
        }

        // 验证状态
        if ($userInfo['is_disable']) {
            throw new OperateException('账号已被禁用!');
        }

        // 登录账户
        $token = UserWidget::granToken(intval($userInfo['id']), $terminal);
        return ['token'=>$token];
    }

    /**
     * 微信登录
     *
     * @param string $code (微信小程序编码)
     * @param string $phoneCode (微信手机号编码)
     * @param int $terminal (客户端[1=微信小程序, 2=微信公众号, 3=H5, 4=PC, 5=安卓, 6=苹果])
     * @return array
     * @throws Exception
     */
    #[ArrayShape(['token' => "string"])]
    public static function wxLogin(string $code, string $phoneCode, int $terminal): array
    {
        // 微信授权
        $response = WeChatService::wxJsCode2session($code);
        $response['terminal'] = $terminal;

        // 获取手机
        if ($phoneCode) {
            $phoneArr = WeChatService::wxPhoneNumber($phoneCode);
            $response['mobile'] = $phoneArr['phoneNumber'];
        }

        // 验证账户
        $userInfo = UserWidget::getUserAuthByResponse($response);
        if (empty($userInfo)) {
            $userId = UserWidget::createUser($response);
        } else {
            $userId = UserWidget::updateUser($response);
        }

        // 登录账户
        $token = UserWidget::granToken($userId, $terminal);
        return ['token'=>$token];
    }

    public static function oaLogin()
    {

    }

    /**
     * 公众号授权链接
     *
     * @param string $url
     * @return array
     */
    #[ArrayShape(['url' => "string"])]
    public static function oaCodeUrl(string $url): array
    {
        $url = WeChatService::oaBuildAuthUrl($url);
        return ['url'=>$url];
    }

}