<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/WaitAdmin
// | github:  https://github.com/topwait/waitadmin
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\frontend\service;

use app\common\basics\Service;
use app\common\enums\ClientEnum;
use app\common\enums\NoticeEnum;
use app\common\exception\OperateException;
use app\common\model\user\User;
use app\common\service\msg\MsgDriver;
use app\common\service\wechat\WeChatService;
use app\frontend\cache\ScanLoginCache;
use app\frontend\cache\WebEnrollCache;
use app\frontend\widgets\UserWidget;
use Exception;
use think\facade\Log;

/**
 * 登录服务类
 */
class LoginService extends Service
{
    /**
     * 注册账号
     *
     * @param array $post   (参数)
     * @param int $terminal (设备)
     * @throws OperateException
     * @throws Exception
     * @author zero
     */
    public static function register(array $post, int $terminal)
    {
        // 接收参数
        $code     = $post['code'];
        $mobile   = $post['mobile'];
        $account  = $post['account'] ?? '';
        $password = $post['password'] ?? '';
        $modelUser = new User();

        // 手机验证
        if (!$modelUser->where(['mobile'=>trim($mobile)])->findOrEmpty()->isEmpty()) {
            throw new OperateException('手机已被占用!');
        }

        // 账号验证
        if (!$modelUser->where(['account'=>trim($account)])->findOrEmpty()->isEmpty()) {
            throw new OperateException('账号已被占用!');
        }

        // 短信验证
        if (!MsgDriver::checkCode(NoticeEnum::REGISTER, $code)) {
            throw new OperateException('验证码错误!');
        }

        // 创建账号
        $userId = UserWidget::createUser([
            'mobile'   => trim($mobile),
            'account'  => trim($account),
            'password' => trim($password),
            'terminal' => $terminal
        ]);

        // 登录账号
        session('userId', $userId);
    }

    /**
     * 账号登录
     *
     * @param $account  (账号)
     * @param $password (密码)
     * @throws OperateException
     * @author zero
     */
    public static function accountLogin(string $account, string $password)
    {
        // 查询账户
        $modelUser = new User();
        $userInfo = $modelUser
            ->field(['id,account,password,salt,is_disable'])
            ->where(['account'=>$account])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证账户
        if (!$userInfo) {
            throw new OperateException('账号不存在了!');
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
        session('userId', $userInfo['id']);
    }

    /**
     * 短信登录
     *
     * @param string $mobile (手机号)
     * @param string $code   (验证码)
     * @throws OperateException
     * @author zero
     */
    public static function mobileLogin(string $mobile, string $code)
    {
        // 短信验证
        if (!MsgDriver::checkCode(NoticeEnum::LOGIN, $code)) {
            throw new OperateException('验证码错误了!');
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
            throw new OperateException('账号不存在了!');
        }

        // 验证状态
        if ($userInfo['is_disable']) {
            throw new OperateException('账号已被禁用!');
        }

        // 登录账户
        session('userId', $userInfo['id']);
    }

    /**
     * 绑定登录
     *
     * @param string $mobile (手机号)
     * @param string $code   (验证码)
     * @param string $sign   (签名值)
     * @param int $terminal  (设备)
     * @return void
     * @throws OperateException
     * @throws Exception
     * @author zero
     */
    public static function baLogin(string $mobile, string $code, string $sign, int $terminal): void
    {
        // 短信验证
        if (!MsgDriver::checkCode(NoticeEnum::BIND_MOBILE, $code)) {
            throw new OperateException('验证码错误');
        }

        // 登录数据
        $response = WebEnrollCache::get($sign);
        if (!$response) {
            throw new OperateException('首次登录绑定手机号异常', 1);
        }

        // 设置参数
        $response['terminal'] = $terminal;
        $response['mobile'] = trim($mobile);

        // 验证账户
        $userInfo = UserWidget::getUserAuthByResponse($response);
        if (empty($userInfo)) {
            $userId = UserWidget::createUser($response);
        } else {
            $response['user_id'] = intval($userInfo['id']);
            $userId = UserWidget::updateUser($response);
        }

        // 登录账户
        session('userId', $userId);
    }

    /**
     * PC微信扫码链接
     *
     * @return array
     * @throws Exception
     * @author zero
     */
    public static function oaCodeUrl(): array
    {
        // 设置扫码有效期
        $uniqId    = uniqid();
        $ip        = request()->ip();
        $microTime = microtime();
        $rand      = rand(1, 1000);
        $state     = md5($uniqId.$ip.$microTime.$rand);

        $event = 'login';
        ScanLoginCache::set($state, ['status'=>ScanLoginCache::$ING]);
        return WeChatService::oaBuildQrCode($state, $event);
    }

    /**
     * PC微信登录
     *
     * @param string $code
     * @param string $state
     * @throws Exception
     * @author zero
     */
    public static function oaLogin(string $code, string $state)
    {
        // 验证时效
        $check = ScanLoginCache::get($state);
        if (empty($check)) {
            ScanLoginCache::set($state, [
                'status' => ScanLoginCache::$FAIL,
                'error'  => '二维码不存在或已失效!'
            ]);
            return;
        }

        // 微信授权
        $response = WeChatService::oaAuth2session($code);
        $response['terminal'] = ClientEnum::PC;

        // 验证账户
        try {
            $userInfo = UserWidget::getUserAuthByResponse($response);
            if (empty($userInfo)) {
                $userId = UserWidget::createUser($response);
            } else {
                $response['user_id'] = $userInfo['user_id'];
                $userId = UserWidget::updateUser($response);
            }

            ScanLoginCache::set($state, [
                'status' => ScanLoginCache::$OK,
                'userId' => $userId
            ]);
        } catch (OperateException $e) {
             ScanLoginCache::set($state, [
                 'status' => ScanLoginCache::$OK,
                 'force'  => true,
                 'error'  => $e->getMessage(),
                 'sign'   => $e->data['sign']??''
             ]);
        }
    }

    /**
     * PC微信扫码检测
     *
     * @param string $key
     * @return array
     * @throws Exception
     * @author zero
     */
    public static function ticketByUser(string $key): array
    {
        $results = ScanLoginCache::get($key);
        if (empty($results['status'])) {
            return $results;
        }

        // 完成登录需强制绑定手机
        if ($results['status'] == ScanLoginCache::$OK && !empty($results['force']) && $results['force']) {
            ScanLoginCache::delete($key);
            return $results;
        }

        // 验证是否存在用户标识ID
        if ($results['status'] == ScanLoginCache::$OK && empty($results['userId'])) {
            $results['status'] = ScanLoginCache::$FAIL;
            $results['error'] = '登录异常,请重新登录!';
            ScanLoginCache::delete($key);
            return $results;
        }

        // 查询用户信息
        $modelUser = new User();
        $userInfo = $modelUser
            ->field(['id,sn,is_disable'])
            ->where(['id'=>intval($results['userId'])])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证用户存在
        if (empty($userInfo)) {
            $results['status'] = ScanLoginCache::$FAIL;
            $results['error'] = '账号异常,请重新登录!';
            ScanLoginCache::delete($key);
            return $results;
        }

        // 验证是否被禁用
        if ($userInfo['is_disable']) {
            $results['status'] = ScanLoginCache::$FAIL;
            $results['error'] = '账号已被停用,请联系客服!';
            ScanLoginCache::delete($key);
            return $results;
        }

        // 登录成功了
        session('userId', $userInfo['id']);
        ScanLoginCache::delete($key);
        $results['error'] = '登录成功';
        return $results;
    }

    /**
     * 重置密码
     *
     * @param array $post (参数)
     * @throws OperateException
     * @author zero
     */
    public static function forgetPwd(array $post): void
    {
        // 接收参数
        $code     = $post['code'];
        $mobile   = $post['mobile'];
        $password = $post['newPassword'];

        // 验证类型
        $field = 'mobile';
        if (!preg_match('/^1[3456789]\d{9}$/', $mobile)) {
            $field = 'email';
        }

        // 编码验证
        if (!MsgDriver::checkCode(NoticeEnum::FORGET_PWD, $code)) {
            throw new OperateException('验证码错误!');
        }

        // 查询账户
        $modelUser = new User();
        $userInfo = $modelUser->field(['id,account,mobile'])
            ->where([$field=>trim($mobile)])
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
}