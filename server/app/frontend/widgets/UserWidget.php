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

namespace app\frontend\widgets;

use app\api\cache\EnrollCache;
use app\common\basics\Service;
use app\common\enums\AttachEnum;
use app\common\exception\OperateException;
use app\common\model\attach\Attach;
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
     * @author zero
     */
    public static function createUser(array $response): int
    {
        // 接收参数
        $snCode   = make_rand_code(new User());
        $terminal = intval($response['terminal']);
        $avatar   = $response['avatarUrl'] ?? '';
        $nickname = $response['nickname']  ?? 'u'.$snCode;
        $account  = $response['account']   ?? 'u'.$snCode;
        $password = $response['password']  ?? '';
        $openId   = $response['openid']    ?? '';
        $unionId  = $response['unionid']   ?? '';
        $mobile   = $response['mobile']    ?? '';
        $gender   = intval($response['gender'] ?? 0);

        // 密码信息
        $salt = make_rand_char(7-1);
        if ($password && !empty($password)) {
            $password = make_md5_str(trim($password), $salt);
        }

        // 强制绑定
        $forceMobile = ConfigUtils::get('login', 'force_mobile', 0);
        if ($forceMobile && !$mobile) {
            $data = ['sign'=>md5(time().make_rand_char(9))];
            EnrollCache::set($data['sign'], $response);
            throw new OperateException('需绑定手机号', 1, $data);
        }

        // 验证账号
        $modelUser = new User();
        $where = array(['account'=>$account,'is_delete'=>0], ['mobile'=>$mobile,'is_delete'=>0]);
        if ($account && !$modelUser->field(['id'])->where($where[0])->findOrEmpty()->isEmpty()) {
            throw new OperateException('账号已被占用了');
        }

        // 验证手机
        if ($mobile && !$modelUser->field(['id'])->where($where[1])->findOrEmpty()->isEmpty()) {
            throw new OperateException('手机已被占用了');
        }

        self::dbStartTrans();
        try {
            // 创建用户
            $user = User::create([
                'sn'              => $snCode,
                'avatar'          => '',
                'mobile'          => $mobile,
                'account'         => $account,
                'password'        => $password,
                'nickname'        => $nickname,
                'salt'            => $salt,
                'gender'          => $gender,
                'last_login_ip'   => request()->ip(),
                'last_login_time' => time(),
                'create_time'     => time(),
                'update_time'     => time()
            ]);

            // 创建授权
            if ($openId || $unionId) {
                UserAuth::create([
                    'user_id'     => $user['id'],
                    'terminal'    => $terminal,
                    'openid'      => $openId,
                    'unionid'     => $unionId,
                    'create_time' => time(),
                    'update_time' => time()
                ]);
            }

            // 下载头像
            try {
                if ($avatar) {
                    $saveTo = 'storage/picture/' . date('Ymd') . '/' . md5((string)$user['id']) . '.jpg';
                    FileUtils::download($avatar, public_path() . $saveTo);
                    User::update(['avatar' => $saveTo], ['id'=>$user['id']]);
                    Attach::create([
                        'uid'       => $user['id'],
                        'cid'       => 0,
                        'quote'     => 1,
                        'file_type' => AttachEnum::getCodeByMsg('picture'),
                        'file_path' => $saveTo,
                        'file_name' => 'avatar'.$user['id'],
                        'file_ext'  => 'jpg',
                        'file_size' => FileUtils::getFileSize(public_path() . $saveTo),
                        'is_user'   => 1,
                        'is_attach' => 0
                    ]);
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
     * @author zero
     */
    public static function updateUser(array $response): int
    {
        // 接收参数
        $terminal = intval($response['terminal']);
        $userId   = intval($response['user_id']);
        $openId   = $response['openid']  ?? '';
        $unionId  = $response['unionid'] ?? '';
        $mobile   = $response['mobile']  ?? '';

        // 用户信息
        $userInfo = (new User())->where(['id'=>$userId, 'is_delete'=>0])->findOrEmpty()->toArray();
        $userAuth = (new UserAuth())->where(['terminal'=>$terminal, 'user_id'=>$userId])->findOrEmpty()->toArray();

        // 验证手机
        $modelUser = new User();
        $where = array(['mobile', '=', $mobile], ['is_delete', '=',0], ['id', '<>', $userId]);
        if ($mobile && !$modelUser->field(['id'])->where($where)->findOrEmpty()->isEmpty()) {
            throw new OperateException('手机已被占用了');
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
                    'unionid'     => $unionId,
                    'openid'      => $openId,
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
     * @author zero
     */
    public static function getUserAuthByResponse(array $response): array
    {
        $unionId  = $response['unionid'] ?? '';
        $openId   = $response['openid']  ?? '';

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
}