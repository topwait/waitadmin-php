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

namespace app\api\widgets;

use app\api\cache\EnrollCache;
use app\api\cache\TokenCache;
use app\common\basics\Service;
use app\common\exception\OperateException;
use app\common\model\user\User;
use app\common\model\user\UserAuth;
use app\common\utils\ConfigUtils;
use app\common\utils\FileUtils;
use Exception;

/**
 * 用户服务装置
 */
class UserWidget extends Service
{
    /**
     * 创建用户
     *
     * @param array $response
     * @return int
     * @throws Exception
     * @author windy
     */
    public static function createUser(array $response): int
    {
        // 接收参数
        $snCode   = make_rand_code(new User());
        $terminal = intval($response['terminal']);
        $avatar   = $response['avatarUrl'] ?? '';
        $account  = $response['account']   ?? 'u'.$snCode;
        $nickname = $response['nickname']  ?? 'u'.$snCode;
        $password = $response['password']  ?? '';
        $mobile   = $response['mobile']    ?? '';
        $openId   = $response['openid']    ?? '';
        $unionId  = $response['unionid']   ?? '';
        $gender   = intval($response['gender'] ?? 0);

        // 强制绑定
        $forceMobile = ConfigUtils::get('login', 'force_mobile', 0);
        if ($forceMobile && !$mobile) {
            $data = ['sign'=>make_md5_str(time().make_rand_char(8))];
            EnrollCache::set($data['sign'], $response);
            throw new OperateException('需绑定手机号', 1, $data);
        }

        // 验证账号
        $modelUser = new User();
        $where = array(['account'=>$account,'is_delete'=>0], ['mobile'=>$mobile,'is_delete'=>0]);
        if ($account && !$modelUser->field(['id'])->where($where[0])->findOrEmpty()->isEmpty()) {
            throw new OperateException('账号已被占用');
        }

        // 验证手机
        if ($mobile && !$modelUser->field(['id'])->where($where[1])->findOrEmpty()->isEmpty()) {
            throw new OperateException('手机已被占用');
        }

        self::dbStartTrans();
        try {
            // 创建用户
            $user = User::create([
                'sn'              => $snCode,
                'avatar'          => $avatar,
                'mobile'          => $mobile,
                'account'         => $account,
                'password'        => $password,
                'nickname'        => $nickname,
                'gender'          => $gender,
                'salt'            => make_rand_char(6),
                'last_login_ip'   => request()->ip(),
                'last_login_time' => time(),
                'create_time'     => time(),
                'update_time'     => time()
            ]);

            // 创建授权
            UserAuth::create([
                'user_id'     => $user['id'],
                'openid'      => $openId,
                'unionid'     => $unionId,
                'terminal'    => $terminal,
                'create_time' => time(),
                'update_time' => time()
            ]);

            // 下载头像
            try {
                if ($avatar) {
                    $saveTo = 'storage/avatar/' . md5($user['id']) . 'jpg';
                    FileUtils::download($avatar, public_path() . $saveTo);
                    User::update(['avatar' => $saveTo], ['id'=>$user['id']]);
                }
            } catch (Exception) {}

            self::dbCommit();
            return intval($user['id']);
        } catch (Exception $e) {
            self::dbRollback();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 更新用户
     *
     * @param array $response
     * @return int
     * @throws Exception
     * @author windy
     */
    public static function updateUser(array $response): int
    {
        // 接收参数
        $userId   = intval($response['user_id']);
        $terminal = intval($response['terminal']);
        $mobile   = $response['mobile']  ?? '';
        $openId   = $response['openid']  ?? '';
        $unionId  = $response['unionid'] ?? '';

        // 用户信息
        $userInfo = (new User())->where(['id'=>$userId])->findOrEmpty()->toArray();
        $userAuth = (new UserAuth())->where(['user_id'=>$userId, 'terminal'=>$terminal])->findOrEmpty()->toArray();

        // 验证手机
        $modelUser = new User();
        $where = array(['mobile', '=', $mobile], ['is_delete', '=',0], ['id', '<>', $userId]);
        if ($mobile && !$modelUser->field(['id'])->where($where)->findOrEmpty()->isEmpty()) {
            throw new OperateException('手机已被占用');
        }

        self::dbStartTrans();
        try {
            // 绑定手机
            if ($mobile && !$userInfo['mobile']) {
                User::update([
                    'mobile' => $mobile,
                    'update_time' => time()
                ], ['id'=>$userId]);
            }

            // 创建授权
            if (!$userAuth) {
                UserAuth::create([
                    'user_id'     => $userId,
                    'openid'      => $openId,
                    'unionid'     => $unionId,
                    'terminal'    => $terminal,
                    'create_time' => time(),
                    'update_time' => time()
                ]);
            }

            // 更新关联
            if (empty($userInfo['unionid']) && $unionId) {
                UserAuth::update([
                    'unionid'     => $response['unionid'],
                    'update_time' => time()
                ], ['user_id'=>$userId, 'terminal'=>$terminal]);
            }

            self::dbCommit();
            return $userId;
        } catch (Exception $e) {
            self::dbRollback();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 获取用户授权信息
     *
     * @param array $response ['openid', 'unionid']
     * @return array
     * @author windy
     */
    public static function getUserAuthByResponse(array $response): array
    {
        $openId   = $response['openid']  ?? '';
        $unionId  = $response['unionid'] ?? '';

        return (new UserAuth())->alias('au')
            ->join('user u', 'au.user_id = u.id')
            ->where(['u.is_delete' => 0])
            ->where(function ($query) use ($openId, $unionId) {
                $query->whereOr(['au.openid'=>$openId]);
                if($unionId){
                    $query->whereOr(['au.unionid'=>$unionId]);
                }
            })->findOrEmpty()->toArray();
    }

    /**
     * 生成令牌
     *
     * @param int $userId
     * @param int $terminal
     * @return string
     */
    public static function granToken(int $userId, int $terminal): string
    {
        $token = make_md5_str(time().$userId);
        TokenCache::set($userId, $terminal, $token);
        return $token;
    }
}