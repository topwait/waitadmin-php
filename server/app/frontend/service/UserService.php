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
use app\common\model\article\ArticleCollect;
use app\common\model\user\User;
use app\common\model\user\UserAuth;
use app\common\service\msg\MsgDriver;
use app\common\service\wechat\WeChatService;
use app\common\utils\AttachUtils;
use app\common\utils\UrlUtils;
use app\frontend\cache\ScanLoginCache;
use app\frontend\widgets\UserWidget;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use think\db\exception\DbException;

/**
 * 用户服务类
 */
class UserService extends Service
{
    /**
     * 用户信息
     *
     * @param int $userId
     * @return array
     * @author zero
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
        $userAuth = $modelUserAuth
            ->where(['user_id'=>$userId])
            ->whereIn('terminal', [ClientEnum::PC, ClientEnum::OA])
            ->findOrEmpty()
            ->toArray();

        if (!$user['avatar']) {
            $defaultAvatar = 'static/common/images/avatar.png';
            $user['avatar'] = UrlUtils::toAbsoluteUrl($defaultAvatar);
        }

        $user['isWeChat'] = (bool) $userAuth;
        $user['last_login_time'] = date('Y-m-d H:i:s', $user['last_login_time']??0);
        return $user;
    }

    /**
     * 用户收藏
     *
     * @param int $userId
     * @return array
     * @throws DbException
     * @author zero
     */
    #[ArrayShape(['count' => "int", 'list' => "array"])]
    public static function collect(int $userId): array
    {
        $modelArticleCollect = new ArticleCollect();
        $lists = $modelArticleCollect->alias('ac')
            ->field(['ac.id,ac.article_id,a.title,a.image,a.browse,ac.create_time'])
            ->where(['ac.user_id'=>$userId])
            ->where(['ac.is_delete'=>0])
            ->join('article a', 'a.id = ac.article_id')
            ->order('create_time desc')
            ->paginate([
                'page'      => $get['page'] ?? 1,
                'list_rows' => 10,
                'var_page'  => 'page'
            ])->toArray();

        return ['count'=>$lists['total'], 'list'=>$lists['data']];
    }

    /**
     * 账号编辑
     *
     * @param array $post
     * @param int $userId
     * @author zero
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
     * @author zero
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
            AttachUtils::markUpdate($user, $post, ['avatar']);
            User::update(['avatar'=>$avatar, 'update_time'=>time()], ['id'=>$userId]);
        }
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
     * 绑定手机
     *
     * @param array $post
     * @param int $userId
     * @throws OperateException
     * @author zero
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
     * @author zero
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

    /**
     * 绑定微信扫码链接
     *
     * @param string $code
     * @param string $state
     * @throws Exception
     * @author zero
     */
    public static function bindWeChat(string $code, string $state): void
    {
        // 验证时效
        $check = ScanLoginCache::get($state);
        if (empty($check) || empty($check['userId'])) {
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
            $response['user_id'] = intval($check['userId']);
            $userId = UserWidget::updateUser($response);

            ScanLoginCache::set($state, [
                'status' => ScanLoginCache::$OK,
                'userId' => $userId
            ]);
        } catch (OperateException|Exception $e) {
            ScanLoginCache::set($state, [
                'status' => ScanLoginCache::$FAIL,
                'error'  => $e->getMessage()
            ]);
        }
    }

    /**
     * 检测微信绑定
     *
     * @param string $key
     * @return array
     * @author zero
     */
    public static function ticketBindWx(string $key): array
    {
        $results = ScanLoginCache::get($key);
        if (empty($results['status'])) {
            return $results;
        }

        // 验证是否存在用户标识ID
        if ($results['status'] == ScanLoginCache::$OK && empty($results['userId'])) {
            $results['status'] = ScanLoginCache::$FAIL;
            $results['error'] = '操作异常,请刷新页面!';
            ScanLoginCache::delete($key);
            return $results;
        }

        // 查询用户信息
        $modelUser = new User();
        $userInfo = $modelUser
            ->field(['id,sn,is_disable'])
            ->where(['is_delete'=>0])
            ->where(['id'=>intval($results['userId'])])
            ->findOrEmpty()
            ->toArray();

        // 验证用户存在
        if (empty($userInfo)) {
            $results['status'] = ScanLoginCache::$FAIL;
            $results['error'] = '账号异常,请重新登录!';
            ScanLoginCache::delete($key);
            return $results;
        }

        // 登录成功了
        ScanLoginCache::delete($key);
        $results['error'] = '绑定成功';
        return $results;
    }
}