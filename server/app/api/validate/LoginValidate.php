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
        'wx'      => ['code', 'phoneCode'],
        'mobile'  => ['code', 'mobile'],
        'account' => ['account', 'password']
    ];
}