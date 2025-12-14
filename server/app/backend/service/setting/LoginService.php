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

namespace app\backend\service\setting;

use app\common\basics\Service;
use app\common\exception\OperateException;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;

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
        $conf = ConfigUtils::get('login') ?? [];
        $logins = ['account'=>'账号登录', 'mobile'=>'短信登录', 'wx'=>'微信登录'];
        $enroll = ['account'=>'账号注册', 'mobile'=>'手机注册', 'oauth'=>'第三方注册'];
        return [
            'login_channels' => $logins,
            'enroll_channels' => $enroll,
            // 基础
            'basis_logo' => UrlUtils::toAbsoluteUrl($conf['basis']['logo'] ?? ''),
            'basis_tips' => $conf['basis']['tips'] ?? '',
            // PC端
            'pc_is_agreement'    => $conf['pc']['is_agreement'] ?? 0,
            'pc_force_mobile'    => $conf['pc']['force_mobile'] ?? 0,
            'pc_default_method'  => $conf['pc']['default_method'] ?? '',
            'pc_usable_channel'  => $conf['pc']['usable_channel'] ?? [],
            'pc_usable_register' => $conf['pc']['usable_register'] ?? [],
            // 微信端
            'wx_is_agreement'    => $conf['wx']['is_agreement'] ?? 0,
            'wx_force_mobile'    => $conf['wx']['force_mobile'] ?? 0,
            'wx_default_method'  => $conf['wx']['default_method'] ?? '',
            'wx_usable_channel'  => $conf['wx']['usable_channel'] ?? [],
            'wx_usable_register' => $conf['wx']['usable_register'] ?? [],
            // H5端
            'h5_is_agreement'    => $conf['h5']['is_agreement'] ?? 0,
            'h5_force_mobile'    => $conf['h5']['force_mobile'] ?? 0,
            'h5_default_method'  => $conf['h5']['default_method'] ?? '',
            'h5_usable_channel'  => $conf['h5']['usable_channel'] ?? [],
            'h5_usable_register' => $conf['h5']['usable_register'] ?? [],
             // 其它端
            'other_is_agreement'    => $conf['other']['is_agreement'] ?? 0,
            'other_force_mobile'    => $conf['other']['force_mobile'] ?? 0,
            'other_default_method'  => $conf['other']['default_method'] ?? '',
            'other_usable_channel'  => $conf['other']['usable_channel'] ?? [],
            'other_usable_register' => $conf['other']['usable_register'] ?? []
        ];
    }

    /**
     * 登录配置保存
     *
     * @param array $post
     * @throws OperateException
     * @author zero
     */
    public static function save(array $post): void
    {
        $clients = ['wx'=>'微信端', 'pc'=>'PC端', 'h5'=>'H5端', 'other'=>'其它端'];
        foreach ($clients as $k => $v) {
            $error = '请指定『'. $v .'』默认登录方式';
            if (empty($post[$k.'_default_method'])) {
                throw new OperateException($error);
            }

            $default = $post[$k.'_default_method'];
            $channel = $post[$k.'_usable_channel'] ?? [];
            if ($channel && !in_array($default, $channel)) {
                $msg = '『' . $v . '』默认登录方式常未启用';
                throw new OperateException($msg);
            }
        }

        if (empty($post['basis_logo'])) {
            throw new OperateException('请设置登录Logo');
        }

        ConfigUtils::set('login', 'basis', [
            'logo' => UrlUtils::toRelativeUrl($post['basis_logo']),
            'tips' => $post['basis_tips'] ?? ''
        ], '基础登录配置');

        ConfigUtils::set('login', 'wx', [
            // 显示登录协议
            'is_agreement'    => $post['wx_is_agreement'] ?? 0,
            // 强制绑定手机
            'force_mobile'    => $post['wx_force_mobile'] ?? 0,
            // 默认登录方式
            'default_method'  => $post['wx_default_method'] ?? '',
            // 可用登录渠道
            'usable_channel'  => $post['wx_usable_channel'] ?? [],
            // 可用注册方式
            'usable_register' => $post['wx_usable_register'] ?? []
        ], '微信端登录方式');

        ConfigUtils::set('login', 'pc', [
            'is_agreement'    => $post['pc_is_agreement'] ?? 0,
            'force_mobile'    => $post['pc_force_mobile'] ?? 0,
            'default_method'  => $post['pc_default_method'] ?? '',
            'usable_channel'  => $post['pc_usable_channel'] ?? [],
            'usable_register' => $post['pc_usable_register'] ?? []
        ], 'PC端登录方式');

        ConfigUtils::set('login', 'h5', [
            'is_agreement'    => $post['h5_is_agreement'] ?? 0,
            'force_mobile'    => $post['h5_force_mobile'] ?? 0,
            'default_method'  => $post['h5_default_method'] ?? '',
            'usable_channel'  => $post['h5_usable_channel'] ?? [],
            'usable_register' => $post['h5_usable_register'] ?? []
        ], 'H5端登录方式');

        ConfigUtils::set('login', 'other', [
            'is_agreement'    => $post['other_is_agreement'] ?? 0,
            'force_mobile'    => $post['other_force_mobile'] ?? 0,
            'default_method'  => $post['other_default_method'] ?? '',
            'usable_channel'  => $post['other_usable_channel'] ?? [],
            'usable_register' => $post['other_usable_register'] ?? [],
        ], '其它登录方式');
    }
}