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

namespace app\api\service;

use app\common\basics\Service;
use app\common\exception\OperateException;
use app\common\model\user\User;
use app\common\model\user\UserAuth;
use app\common\utils\FileUtils;
use app\common\utils\UrlUtils;

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
                $ext    = FileUtils::getFileExt($value);
                $avatar = UrlUtils::toRelativeUrl($value);
                $source = UrlUtils::toRoot($avatar);
                $target = 'storage/avatar/user/'.date('Ymd').'/'.md5($userId).'.'.$ext;
                FileUtils::move($source, UrlUtils::toRoot($target));
                User::update(['avatar'=>$target, 'update_time'=>time()], ['id'=>$userId]);
                break;
        }
    }
}