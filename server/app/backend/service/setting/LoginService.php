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
            // 微信端显示登录协议
            'wx_is_agreement' => intval($login['wx_is_agreement'] ?? 0),
            // 微信端强制绑定手机
            'wx_force_mobile' => intval($login['wx_force_mobile'] ?? 0),
            // 微信端默认方式
            'wx_default' => $login['wx_default'] ?? '',
            // 微信端登录渠道
            'wx_channel' => $login['wx_channel'] ?? [],

            // PC端显示登录协议
            'pc_is_agreement' => intval($login['pc_is_agreement'] ?? 0),
            // PC端强制绑定手机
            'pc_force_mobile' => intval($login['pc_force_mobile'] ?? 0),
            // PC端默认方式
            'pc_default' => $login['pc_default'] ?? [],
            // PC端登录渠道
            'pc_channel' => $login['pc_channel'] ?? [],

            // H5端显示登录协议
            'h5_is_agreement' => intval($login['h5_is_agreement'] ?? 0),
            // H5端强制绑定手机
            'h5_force_mobile' => intval($login['h5_force_mobile'] ?? 0),
            // H5端默认方式
            'h5_default' => $login['h5_default'] ?? [],
            // H5端登录渠道
            'h5_channel' => $login['h5_channel'] ?? [],

            // 其它端显示登录协议
            'other_is_agreement' => intval($login['other_is_agreement'] ?? 0),
            // 其它端强制绑定手机
            'other_force_mobile' => intval($login['other_force_mobile'] ?? 0),
            // 其它端默认方式
            'other_default' => $login['other_default'] ?? [],
            // 其它端登录渠道
            'other_channel' => $login['other_channel'] ?? []
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
        ConfigUtils::set('login', 'login_method', $post['login_method'] ?? '', '默认登录方式');
        ConfigUtils::set('login', 'login_channel', $post['login_channel'] ?? [], '可用登录渠道');

//        ConfigUtils::set('login', 'auths_mobile', intval($post['auths_mobile'] ?? 0), '微信授权手机');
//        ConfigUtils::set('login', 'login_modes', $post['login_modes'] ?? [], '通用登录方式');
//        ConfigUtils::set('login', 'login_other', $post['login_other'] ?? [], '第三方登录');
    }
}