<?php


namespace app\frontend\validate;


use app\common\basics\Validate;

/**
 * 登录参数验证器
 *
 * Class LoginValidate
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