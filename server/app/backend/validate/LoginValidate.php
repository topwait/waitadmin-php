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

namespace app\backend\validate;


use app\common\basics\Validate;

/**
 * 登录参数验证器
 *
 * Class LoginValidate
 * @package app\backend\validate
 */
class LoginValidate extends Validate
{
    protected $rule = [
        'username' => 'require|min:2|max:8',
        'password' => 'require|min:6|max:18',
        'captcha'  => 'require|captcha'
    ];

    protected $message = [
        'username.min'        => '用户名或密码有误！',
        'username.max'        => '用户名或密码有误！',
        'password.min'        => '用户名或密码有误！',
        'password.max'        => '用户名或密码有误！',
        'captcha.captcha'     => '验证码错误'
    ];
}