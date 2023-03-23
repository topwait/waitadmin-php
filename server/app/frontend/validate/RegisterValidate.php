<?php


namespace app\frontend\validate;


use app\common\basics\Validate;

class RegisterValidate extends Validate
{
    protected $rule = [
        'mobile'   => 'require|mobile',
        'nickname' => 'require|min:2|max:20',
        'username' => 'require|min:2|max:20',
        'password' => 'require|min:6|max:18',
        'captcha'  => 'require|captcha'
    ];
}