<?php

namespace app\frontend\validate;

use app\common\basics\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'field'  => 'require',
        'sign'   => 'require',
        'email'  => 'require|email',
        'mobile' => 'require|mobile',
        'newPwd' => 'require|min:6|max:18',
        'oldPwd' => 'min:6|max:18'
    ];

    protected $scene = [
        'changeSign'     => ['field', 'sign'],
        'changeEmail'    => ['field', 'email'],
        'changeMobile'   => ['field', 'mobile'],
        'changePassword' => ['field', 'newPwd', 'oldPwd'],
    ];
}