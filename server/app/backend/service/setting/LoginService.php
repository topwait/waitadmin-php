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

namespace app\backend\service\setting;

use app\common\basics\Service;
use app\common\utils\ConfigUtils;

/**
 * 登录配置服务类
 */
class LoginService extends Service
{
    /**
     * 登录配置详情
     *
     * @return array
     * @author zero
     */
    public static function detail(): array
    {
        $login = ConfigUtils::get('login');
        $detail['login'] = [
            'is_agreement' => intval($login['is_agreement'] ?? 0),
            'force_mobile' => intval($login['force_mobile'] ?? 0),
            'login_modes'  => $login['login_modes'] ?? [],
            'login_other'  => $login['login_other'] ?? [],
        ];

        return $detail['login'];
    }

    /**
     * 登录配置保存
     *
     * @param array $post
     * @author zero
     */
    public static function save(array $post): void
    {
        ConfigUtils::set('login', 'is_agreement', intval($post['is_agreement'] ?? 0), '显示登录协议');
        ConfigUtils::set('login', 'force_mobile', intval($post['force_mobile'] ?? 0), '强制绑定手机');
        ConfigUtils::set('login', 'login_modes', json_encode($post['login_modes'] ?? []), '通用登录方式');
        ConfigUtils::set('login', 'login_other', json_encode($post['login_other'] ?? []), '第三方登录');
    }
}