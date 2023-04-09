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

namespace app\backend\service;


use app\common\basics\Service;
use app\common\exception\OperateException;
use app\common\model\auth\AuthAdmin;
use think\facade\Cache;

/**
 * 登录服务类
 */
class LoginService extends Service
{
    /**
     * 登录系统
     *
     * @param array $post
     * @throws OperateException
     * @author zero
     */
    public static function login(array $post): void
    {
        // 登录限制
        $loginFailCount = Cache::get('login:fail:'.$post['username']);
        $surplusCount = 5 - intval($loginFailCount??0);
        if ($loginFailCount && $surplusCount <= 0) {
            throw new OperateException('密码错误次数过多,账号已暂时锁定!');
        }

        // 账户查询
        $model = new AuthAdmin();
        $adminUser = $model
            ->field(true)
            ->where(['is_delete' => 0])
            ->where(['username' => $post['username']])
            ->findOrEmpty()
            ->toArray();

        // 账户验证
        if (!$adminUser) {
            Cache::set('login:fail:'.$post['username'], intval($loginFailCount)+1, 600);
            throw new OperateException('用户名或密码错误,您还可以尝试['.$surplusCount.']次!');
        }

        // 账户密码
        $password = make_md5_str($post['password'], $adminUser['salt']);
        if ($adminUser['password'] !== $password) {
            Cache::set('login:fail:'.$post['username'], intval($loginFailCount)+1, 600);
            throw new OperateException('用户名或密码错误,您还可以尝试['.$surplusCount.']次!');
        }

        // 账户禁用
        if ($adminUser['is_disable']) {
            throw new OperateException('当前用户已被禁止登录!');
        }

        // 账户更新
        $model->where(['id' => $adminUser['id']])->update([
            'last_login_ip'   => request()->ip(),
            'last_login_time' => time()
        ]);

        // 账户记录
        session('adminUser', $adminUser);
    }
}