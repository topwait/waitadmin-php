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

namespace app\api\widgets;

use app\api\cache\EnrollCache;
use app\api\cache\LoginCache;
use app\common\basics\Service;
use app\common\enums\ClientEnum;
use app\common\exception\OperateException;
use app\common\model\user\User;
use app\common\model\user\UserAuth;
use app\common\service\storage\StorageDriver;
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
     * @author zero
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

        // 密码信息
        $salt = make_rand_char(6);
        if ($password) {
            $password = make_md5_str(trim($password), $salt);
        }

        // 强制绑定
        $forceMobile = ConfigUtils::get('login', 'force_mobile', 0);
        if ($forceMobile && !$mobile) {
            $data = ['sign'=>md5(time().make_rand_char(8))];
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
                'avatar'          => '/static/common/images/avatar.png',
                'mobile'          => $mobile,
                'account'         => $account,
                'password'        => $password,
                'nickname'        => $nickname,
                'gender'          => $gender,
                'salt'            => $salt,
                'last_login_ip'   => request()->ip(),
                'last_login_time' => time(),
                'create_time'     => time(),
                'update_time'     => time()
            ]);

            // 创建授权
            if ($openId || $unionId) {
                UserAuth::create([
                    'user_id'     => $user['id'],
                    'openid'      => $openId,
                    'unionid'     => $unionId,
                    'terminal'    => $terminal,
                    'create_time' => time(),
                    'update_time' => time()
                ]);

                // 公众号端同步授权 (因为openId/unionid是一样的)
                // 因为PC端也是采用公众号扫码的方式授权,如果你改用开放平台的方式,那就不一样了
                $oaAuth = (new UserAuth())->where(['user_id'=>$user['id']])->where(['terminal'=>ClientEnum::PC])->findOrEmpty();
                if ($oaAuth->isEmpty()) {
                    UserAuth::create([
                        'user_id'     => $user['id'],
                        'terminal'    => ClientEnum::PC,
                        'openid'      => $openId,
                        'unionid'     => $unionId,
                        'create_time' => time(),
                        'update_time' => time()
                    ]);
                }
            }

            // 下载头像
            self::downUpdateAvatar($avatar, intval($user['id']), $user['create_time']);

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
     * @author zero
     */
    public static function updateUser(array $response): int
    {
        // 接收参数
        $userId   = intval($response['user_id']);
        $terminal = intval($response['terminal']);
        $mobile   = $response['mobile']  ?? '';
        $openId   = $response['openid']  ?? '';
        $unionId  = $response['unionid'] ?? '';
        $avatar   = $response['avatar'] ?? '';

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
            if (!$userAuth && ($openId || $unionId)) {
                UserAuth::create([
                    'user_id'     => $userId,
                    'openid'      => $openId,
                    'unionid'     => $unionId,
                    'terminal'    => $terminal,
                    'create_time' => time(),
                    'update_time' => time()
                ]);

                $oaAuth = (new UserAuth())->where(['user_id'=>$userId])->where(['terminal'=>ClientEnum::PC])->findOrEmpty();
                if ($oaAuth->isEmpty()) {
                    UserAuth::create([
                        'user_id'     => $userId,
                        'terminal'    => ClientEnum::PC,
                        'openid'      => $openId,
                        'unionid'     => $unionId,
                        'create_time' => time(),
                        'update_time' => time()
                    ]);
                }
            }

            // 更新关联
            if (empty($userInfo['unionid']) && $unionId) {
                UserAuth::update([
                    'unionid'     => $response['unionid'],
                    'update_time' => time()
                ], ['user_id'=>$userId, 'terminal'=>$terminal]);
            }

            // 更新头像
            if (!$userInfo['avatar']) {
                self::downUpdateAvatar($avatar, intval($userInfo['id']), $userInfo['create_time']);
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
     * @author zero
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
        LoginCache::set($userId, $terminal, $token);
        return $token;
    }

    /**
     * 下载并更新用户头像
     *
     * @param string $avatarUrl  (http头像链接)
     * @param int $userId        (用户ID)
     * @param string $createTime (用户创建日期)
     * @author zero
     */
    private static function downUpdateAvatar(string $avatarUrl, int $userId, string $createTime): void
    {
        try {
            if ($avatarUrl) {
                $date = date('Ymd', strtotime($createTime));
                $saveTo = 'storage/avatars/' . $date . '/' . md5((string)$userId) . '.jpg';

                $engine = ConfigUtils::get('storage', 'default', 'local');
                if ($engine === 'local') {
                    FileUtils::download($avatarUrl, public_path() . $saveTo);
                } else {
                    $storageDriver = new StorageDriver();
                    $storageDriver->fetch($avatarUrl, $saveTo);
                }

                User::update(['avatar'=>$saveTo], ['id'=>$userId]);
            }
        } catch (Exception) {}
    }
}