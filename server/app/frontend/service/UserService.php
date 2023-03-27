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
use app\common\enums\NoticeEnum;
use app\common\exception\OperateException;
use app\common\model\user\User;
use app\common\model\user\UserAuth;
use app\common\service\msg\MsgDriver;
use app\common\service\wechat\WeChatService;
use app\common\utils\FileUtils;
use app\common\utils\UrlUtils;
use app\frontend\validate\UserValidate;
use Exception;

/**
 * 用户服务类
 *
 * Class UsersService
 * @package app\frontend\service
 */
class UserService extends Service
{
    /**
     * 用户信息
     *
     * @param int $userId
     * @return array
     * @author windy
     */
    public static function info(int $userId): array
    {
        $modelUser = new User();
        $user = $modelUser->withoutField('password,salt,is_disable,is_delete,delete_time')
            ->where(['id'=> $userId])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        $modelUserAuth = new UserAuth();
        $userAuth = $modelUserAuth->where(['user_id'=>$userId])->findOrEmpty()->toArray();

        $user['isWeChat'] = (bool) $userAuth;
        $user['avatar']   = UrlUtils::toAbsoluteUrl($user['avatar']);
        $user['last_login_time'] = date('Y-m-d H:i:s', $user['last_login_time']);
        return $user;
    }

    /**
     * 账号编辑
     *
     * @param array $post
     * @param int $userId
     * @author windy
     */
    public static function edit(array $post, int $userId): void
    {
        $nickname = $post['nickname'];
        $gender   = $post['gender'];
        $sign     = $post['sign']??'';

        User::update([
            'nickname'    => $nickname,
            'gender'      => $gender,
            'sign'        => $sign,
            'update_time' => time()
        ], ['id'=>$userId]);
    }

    /**
     * 修改头像
     *
     * @param array $post
     * @param int $userId
     * @throws OperateException
     * @author windy
     */
    public static function changeAvatar(array $post, int $userId): void
    {
        $avatarUrl = trim($post['avatar']);

        $modelUser = new User();
        $user = $modelUser->withoutField('is_disable,is_delete,delete_time')
            ->where(['id'=> $userId])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        if (!$user) {
            throw new OperateException('账号疑是丢失了!');
        }

        $avatar = UrlUtils::toRelativeUrl($avatarUrl);
        if ($user['avatar'] !== $avatar) {
            $ext    = FileUtils::getFileExt($avatar);
            $source = UrlUtils::toRoot($avatar);
            $target = 'storage/avatar/user/'.date('Ymd').'/'.md5((string)$userId).'.'.$ext;
            FileUtils::move($source, UrlUtils::toRoot($target));
            User::update(['avatar'=>$target, 'update_time'=>time()], ['id'=>$userId]);
        }
    }

    /**
     * 修改密码
     *
     * @param array $post
     * @param int $userId
     * @throws OperateException
     * @author windy
     */
    public static function changePwd(array $post, int $userId): void
    {
        $newPassword = trim($post['newPassword']);
        $oldPassword = trim($post['oldPassword']);

        $modelUser = new User();
        $user = $modelUser->withoutField('is_disable,is_delete,delete_time')
            ->where(['id'=> $userId])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        if (!$user) {
            throw new OperateException('账号疑是丢失了!');
        }

        $oldPwd = make_md5_str($oldPassword, $user['salt']);
        if ($user['password'] && $user['password'] !== $oldPwd) {
            throw new OperateException('旧密码校验错误!');
        }

        $salt = make_rand_char(5);
        $pwd  = make_md5_str($newPassword, $salt);
        User::update([
            'salt'        => $salt,
            'password'    => $pwd,
            'update_time' => time()
        ], ['id'=>$userId]);
    }

    /**
     * 绑定微信
     *
     * @param array $get
     * @param int $userId
     * @throws OperateException
     * @throws Exception
     */
    public static function bindWeChat(array $get, int $userId): void
    {
        // 接收参数
        $code = $get['code'];

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
            throw new OperateException('已绑定了,请勿重复操作!');
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
                'openid'   => $response['openid']??'',
                'unionid'  => $response['unionid']??'',
                'terminal' => 1,
                'create_time' => time(),
                'update_time' => time()
            ]);
        }
    }

    /**
     * 绑定手机
     *
     * @param array $post
     * @param int $userId
     * @throws OperateException
     * @author windy
     */
    public static function bindMobile(array $post, int $userId): void
    {
        $mobile = strtolower(trim($post['mobile']));
        $code   = strtolower(trim($post['code']));

        $modelUser = new User();
        $user = $modelUser->withoutField('is_disable,is_delete,delete_time')
            ->where(['id'=> $userId])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        if ($user['mobile'] === $mobile) {
            throw new OperateException('与原手机相同!');
        }

        if (!MsgDriver::checkCode(NoticeEnum::BIND_MOBILE, $code)) {
            throw new OperateException('验证码错误!');
        }

        if (!$modelUser->field(['id'])
            ->where(['mobile' => $mobile])
            ->where(['is_delete' => 0])
            ->findOrEmpty()
            ->isEmpty()
        ) { throw new OperateException('该手机已绑定其他账号!'); }

        User::update([
            'mobile'      => $mobile,
            'update_time' => time()
        ], ['id'=>$userId]);
    }

    /**
     * 绑定邮箱
     *
     * @param array $post
     * @param int $userId
     * @throws OperateException
     * @author windy
     */
    public static function bindEmail(array $post, int $userId): void
    {
        $email = strtolower(trim($post['email']));
        $code  = strtolower(trim($post['code']));

        $modelUser = new User();
        $user = $modelUser->withoutField('is_disable,is_delete,delete_time')
            ->where(['id'=> $userId])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        if ($user['email'] === $email) {
            throw new OperateException('与原邮箱相同!');
        }

        if (!MsgDriver::checkCode(NoticeEnum::BIND_EMAIL, $code)) {
            throw new OperateException('验证码错误!');
        }

        if (!$modelUser->field(['id'])
            ->where(['email' => $email])
            ->where(['is_delete' => 0])
            ->findOrEmpty()
            ->isEmpty()
        ) { throw new OperateException('该邮箱已绑定其他账号!'); }

        User::update([
            'email'       => $email,
            'update_time' => time()
        ], ['id'=>$userId]);
    }

}