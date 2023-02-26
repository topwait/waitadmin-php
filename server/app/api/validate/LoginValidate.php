<?php

namespace app\api\validate;

use app\common\basics\Validate;

/**
 * 登录参数验证器
 */
class LoginValidate extends Validate
{
    protected $rule = [
        'url'         => 'require|url',
        'code'        => 'require|alphaDash',
        'sign'        => 'alphaDash|max:32',
        'wxCode'      => 'alphaDash|max:200',
        'mobile'      => 'require|mobile|min:11|max:11',
        'account'     => 'require|alphaDash|min:2|max:20',
        'password'    => 'require|alphaDash|min:6|max:20',
        'newPassword' => 'require|alphaDash|min:6|max:20',
        'oldPassword' => 'require|alphaDash|min:6|max:20',
    ];

    /**
     * 注册账号
     *
     * @return LoginValidate
     */
    public function sceneRegister(): LoginValidate
    {
        $this->field = [
            'account'  => '账号',
            'password' => '密码',
            'mobile'   => '手机号',
            'code'     => '验证码',
        ];
        return $this->only(['account', 'password', 'mobile', 'code']);
    }

    /**
     * 修改密码

     * @return LoginValidate
     */
    public function sceneChangePwd(): LoginValidate
    {
        $this->field = [
            'newPassword' => '新密码',
            'oldPassword' => '原密码'
        ];
        return $this->only(['newPassword', 'oldPassword']);
    }

    /**
     * 忘记密码

     * @return LoginValidate
     */
    public function sceneForgetPwd(): LoginValidate
    {
        $this->field = [
            'mobile'   => '手机号',
            'code'     => '验证码',
            'password' => '新密码'
        ];
        return $this->only(['mobile', 'code', 'password']);
    }

    /**
     * 登录场景
     *
     * @var string[][]
     */
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
        'account'   => ['account', 'password']
    ];
}