<?php

namespace app\api\validate;

use app\common\basics\Validate;

class LoginValidate extends Validate
{
    protected $rule = [
        'url'       => 'require|url',
        'code'      => 'require|alphaDash',
        'sign'      => 'alphaDash|max:32',
        'wxCode'    => 'alphaDash|max:200',
        'mobile'    => 'require|mobile|min:11|max:11',
        'account'   => 'require|alphaDash|min:2|max:20',
        'password'  => 'require|alphaDash|min:6|max:20'
    ];

    protected $scene = [
        // 授权的链接
        'url'       => ['url'],
        // 公众号登录
        'oa'        => ['code'],
        // 微信登录
        'wx'        => ['code', 'wxCode'],
        // 绑定登录
        'bind'      => ['mobile', 'code', 'sign'],
        // 短信登录
        'mobile'    => ['mobile', 'code'],
        // 账号登录
        'account'   => ['account', 'password'],
        // 重设密码
        'forget'    => ['mobile', 'code', 'password'],
        // 注册账号
        'register'  => ['account', 'password', 'mobile', 'code']
    ];
}