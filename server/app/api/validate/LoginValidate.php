<?php

namespace app\api\validate;

use app\common\basics\Validate;

class LoginValidate extends Validate
{
    protected $rule = [
        'code'      => 'require|alphaDash',
        'phoneCode' => 'alphaDash|max:200',
        'mobile'    => 'require|mobile',
        'account'   => 'require|alphaDash',
        'password'  => 'require|min:6|max:20'
    ];

    protected $scene = [
        // 授权的链接
        'url'       => ['url'],
        // 公众号登录
        'oa'        => ['code'],
        // 微信登录
        'wx'        => ['code', 'phoneCode'],
        // 短信登录
        'mobile'    => ['code', 'mobile'],
        // 账号登录
        'account'   => ['account', 'password'],
        // 重设密码
        'forget'    => ['mobile', 'code', 'password'],
        // 注册账号
        'register'  => ['account', 'password', 'mobile', 'code']
    ];
}