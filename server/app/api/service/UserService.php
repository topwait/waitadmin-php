<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/waitadmin-php
// | github:  https://github.com/topwait/waitadmin-php
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\api\service;

use app\common\basics\Service;
use app\common\enums\ClientEnum;
use app\common\enums\GenderEnum;
use app\common\enums\NoticeEnum;
use app\common\exception\OperateException;
use app\common\model\user\User;
use app\common\model\user\UserAuth;
use app\common\service\msg\MsgDriver;
use app\common\service\wechat\WeChatService;
use app\common\utils\FileUtils;
use app\common\utils\UrlUtils;
use Exception;

/**
 * 用户服务类
 */
class UserService extends Service
{
    /**
     * 个人中心
     *
     * @param int $id
     * @return array
     * @author zero
     */
    public static function center(int $id): array
    {
        $modelUser = new User();
        $user = $modelUser
            ->field(['id,sn,account,nickname,avatar,mobile,email,gender'])
            ->where(['id'=>$id])
            ->where(['is_delete'=>0])
            ->withAttr(['gender' => function ($val) {
                return GenderEnum::getMsgByCode($val);
            }])
            ->withAttr(['is_wechat' => function() use ($id) {
                $modelUserAuth = new UserAuth();
                return !$modelUserAuth->field(['id'])
                    ->where(['user_id'=>$id])
                    ->whereIn('terminal', [ClientEnum::MNP, ClientEnum::OA, ClientEnum::H5])
                    ->findOrEmpty()
                    ->isEmpty();
            }])
            ->append(['is_wechat'])
            ->findOrEmpty()
            ->toArray();

        if (!$user['avatar']) {
            $defaultAvatar = 'static/common/images/avatar.png';
            $user['avatar'] = UrlUtils::toAbsoluteUrl($defaultAvatar);
        }

        return $user;
    }

    /**
     * 用户信息
     *
     * @param int $id
     * @return array
     * @author zero
     */
    public static function info(int $id): array
    {
        $modelUser = new User();
        $user = $modelUser
            ->field(['id,sn,account,nickname,avatar,mobile,email,gender'])
            ->where(['id'=>$id])
            ->where(['is_delete'=>0])
            ->withAttr(['isWeiChat' => function() use ($id) {
                $modelUserAuth = new UserAuth();
                return !$modelUserAuth->field(['id'])
                    ->where(['user_id'=>$id])
                    ->whereIn('terminal', [ClientEnum::MNP, ClientEnum::OA, ClientEnum::H5])
                    ->findOrEmpty()
                    ->isEmpty();
            }])
            ->append(['isWeiChat'])
            ->findOrEmpty()
            ->toArray();

        if (isset($user['avatar']) && !$user['avatar']) {
            $defaultAvatar = 'static/common/images/avatar.png';
            $user['avatar'] = UrlUtils::toAbsoluteUrl($defaultAvatar);
        }

        return $user;
    }

    /**
     * 用户编辑
     *
     * @param array $post
     * @param int $userId
     * @throws OperateException
     * @author zero
     */
    public static function edit(array $post, int $userId): void
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
                $ext    = FileUtils::getFileExt($value);
                $avatar = UrlUtils::toRelativeUrl($value);
                $source = UrlUtils::toRoot($avatar);
                $target = 'storage/avatar/user/'.date('Ymd').'/'.md5((string)$userId).'.'.$ext;
                FileUtils::move($source, UrlUtils::toRoot($target));
                User::update(['avatar'=>$target, 'update_time'=>time()], ['id'=>$userId]);
                break;
        }
    }

    /**
     * 验证密码
     *
     * @param string $password
     * @param int $userId
     * @return bool
     */
    public static function checkPwd(string $password, int $userId): bool
    {
        // 查询账户
        $modelUser = new User();
        $user = $modelUser->field(['id,password,salt'])
            ->where(['id'=>$userId])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证密码
        $originalPwd = make_md5_str($password, $user['salt']);
        if ($user['password'] !== $originalPwd) {
            return false;
        }

        return true;
    }

    /**
     * 忘记密码
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

        // 短信验证
        if (!MsgDriver::checkCode(NoticeEnum::FORGET_PWD, $code)) {
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
     * 修改密码
     *
     * @param array $post
     * @param int $userId
     * @throws OperateException
     * @author zero
     */
    public static function changePwd(array $post, int $userId): void
    {
        // 接收参数
        $newPassword = $post['newPassword'];
        $oldPassword = $post['oldPassword'];

        // 查询账户
        $modelUser = new User();
        $user = $modelUser->field(['id,password,salt'])
            ->where(['id'=>$userId])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证账户
        if (!$user) {
            throw new OperateException('检测到用户已不存在!');
        }

        // 验证密码
        $originalPwd = make_md5_str($oldPassword, $user['salt']);
        if ($user['password'] !== $originalPwd) {
            throw new OperateException('检测到旧密码不正确!');
        }

        // 更新密码
        $salt = make_rand_char(6);
        User::update([
            'salt'        => $salt,
            'password'    => make_md5_str($newPassword, $salt),
            'update_time' => time()
        ], ['id'=>$userId]);
    }

    /**
     * 绑定微信
     *
     * @param array $post (参数)
     * @param int $userId (用户ID)
     * @throws OperateException
     * @throws Exception
     * @author zero
     */
    public static function bindWeChat(array $post, int $userId): void
    {
        // 接收参数
        $code = $post['code'];

        // 微信授权
        $response = WeChatService::wxJsCode2session($code);

        // 验证账户
        $modeUserAuth = new UserAuth();
        $userAuth = $modeUserAuth->field(['id,openid,unionid,terminal'])
            ->where(['id'=>$userId])
            ->where(['terminal'=>1])
            ->findOrEmpty()
            ->toArray();

        // 验证绑定
        if ($userAuth
            && $userAuth['openid'] == $response['openid']
            && $userAuth['unionid'] == $response['unionid']) {
            throw new OperateException('已绑定,请勿重复操作!');
        }

        //更新授权
        if ($userAuth) {
            UserAuth::update([
                'openid'      => $response['openid'] ?? $userAuth['openid'],
                'unionid'     => $response['unionid'] ?? $userAuth['unionid'],
                'update_time' => time(),
            ], ['id'=>intval($userAuth['id'])]) ;
        } else {
            UserAuth::create([
                'user_id'  => $userId,
                'openid'   => $response['openid'],
                'unionid'  => $response['unionid'],
                'terminal' => 1,
                'create_time' => time(),
                'update_time' => time()
            ]);
        }
    }

    /**
     * 绑定手机
     *
     * @param array $post (参数)
     * @param int $userId (用户ID)
     * @throws OperateException
     * @throws Exception
     * @author zero
     */
    public static function bindMobile(array $post, int $userId): void
    {
        // 接收参数
        $mobile = $post['mobile']??'';
        $code   = $post['code']??'';
//        $type   = $post['type']??'';

//        if ($type === 'code') {
//            // 微信验证
//            $phoneArr = WeChatService::wxPhoneNumber($code);
//            $mobile = $phoneArr['phoneNumber'];
//        } else {
//            // 短信验证
//            $nCode = $type === 'change' ? NoticeEnum::CHANGE_MOBILE : NoticeEnum::BIND_MOBILE;
//            if (!MsgDriver::checkCode($nCode, $code, true)) {
//                throw new OperateException('验证码错误');
//            }
//        }

        //  $nCode = $type === 'change' ? NoticeEnum::CHANGE_MOBILE : NoticeEnum::BIND_MOBILE;


        // 短信验证
        $nCode = NoticeEnum::BIND_MOBILE;
        if (!MsgDriver::checkCode($nCode, $code, true)) {
            throw new OperateException('验证码错误');
        }

        // 查询用户
        $modeUser = new User();
        $user = $modeUser->field(['id,mobile'])
            ->where(['id'=>$userId])
            ->where(['is_delete'=>$userId])
            ->findOrEmpty()
            ->toArray();

        // 验证用户
        if (!$user) {
            throw new OperateException('检测到用户已不存在!');
        }

        // 验证密码

        // 查询手机
        $mobileCheck = $modeUser->field(['id'])
            ->where(['id', '<>', $userId])
            ->where(['mobile'=>$mobile])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证手机
        if ($mobileCheck) {
            throw new OperateException('检测到手机号已被占用!');
        }

        // 更新信息
        User::update([
            'mobile' => $mobile,
            'update_time' => time()
        ], ['id'=>$userId]);
    }

    /**
     * 绑定邮箱
     *
     * @param array $post (参数)
     * @param int $userId (用户ID)
     * @throws OperateException
     * @author zero
     */
    public static function bindEmail(array $post, int $userId): void
    {
        // 接收参数
        $email = $post['email'];
        $code  = $post['code'];

        // 短信验证
        if (!MsgDriver::checkCode(NoticeEnum::BIND_EMAIL, $code)) {
            throw new OperateException('验证码错误');
        }

        // 查询用户
        $modeUser = new User();
        $user = $modeUser->field(['id,email'])
            ->where(['id'=>$userId])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证用户
        if (!$user) {
            throw new OperateException('检测到用户已不存在!');
        }

        // 查询邮箱
        $emailCheck = $modeUser->field(['id'])
            ->where([['id', '<>', $userId]])
            ->where(['email'=>$email])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 验证邮箱
        if ($emailCheck) {
            throw new OperateException('检测到邮箱号已被占用!');
        }

        // 删验证码
        MsgDriver::useCode(NoticeEnum::BIND_EMAIL, $code);

        // 更新信息
        User::update([
            'email' => $email,
            'update_time' => time()
        ], ['id'=>$userId]);
    }
}