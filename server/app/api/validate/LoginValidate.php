<?php

namespace app\api\validate;

use app\common\basics\Validate;

class LoginValidate extends Validate
{
    protected $rule = [
        'code'     => 'require|max:6',
        'mobile'   => 'require|mobile',
        'account'  => 'require',
        'password' => 'require'
    ];

    protected $scene = [
        'wx'      => ['code'],
        'mobile'  => ['code', 'mobile'],
        'account' => ['account', 'password']
    ];
}