<?php

namespace app\frontend\validate;

use app\common\basics\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'type'   => 'require',
        'sign'   => 'require',
        'email'  => 'require|email',
        'mobile' => 'require|mobile',
        'newPwd' => 'require|min:6|max:18',
        'oldPwd' => 'require|min:6|max:18'
    ];

    protected $scene = [
        'changeSign'     => ['type', 'sign'],
        'changeEmail'    => ['type', 'email'],
        'changeMobile'   => ['type', 'mobile'],
        'changePassword' => ['type', 'newPwd', 'oldPwd'],
    ];
}