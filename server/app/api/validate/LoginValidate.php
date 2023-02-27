<?php

namespace app\api\validate;

use app\common\basics\Validate;

/**
 * 登录参数验证器
 */
class LoginValidate extends Validate
{
    /**
     * 场景验证
     *
     * @return LoginValidate
     * @author windy
     */
    public function sceneScene(): LoginValidate
    {
        return $this->only(['scene'])
            ->append('scene', 'require|in:account,mobile,wx,oa,ba');
    }

    /**
     * 账号登录
     *
     * @return LoginValidate
     * @author windy
     */
    public function sceneAccount(): LoginValidate
    {
        $this->message = [
            'account'    => '账号或密码错误',
            'password'   => '账号或密码错误'
        ];

        return $this->only(['account', 'password'])
            ->append('account', 'require|alphaNum|min:2|max:20')
            ->append('password', 'require|alphaNum|min:6|max:20');
    }

    /**
     * 短信登录
     *
     * @return LoginValidate
     * @author windy
     */
    public function sceneMobile(): LoginValidate
    {
        $this->message = [
            'mobile' => '手机号错误',
            'code'   => '验证码错误'
        ];

        return $this->only(['mobile', 'code'])
            ->append('mobile', 'require|mobile|min:11|max:11')
            ->append('code', 'require|alphaDash|max:6');
    }

    /**
     * 微信登录
     *
     * @return LoginValidate
     * @author windy
     */
    public function sceneWx(): LoginValidate
    {
        return $this->only(['code', 'wxCode'])
            ->append('code', 'require|alphaDash|max:200')
            ->append('wxCode', 'alphaDash|max:200');
    }

    /**
     * 公众登录
     *
     * @return LoginValidate
     * @author windy
     */
    public function sceneOa(): LoginValidate
    {
        return $this->only(['code'])
            ->append('code', 'require|alphaDash|max:200');
    }

    /**
     * 绑定登录
     *
     * @return LoginValidate
     * @author windy
     */
    public function sceneBa(): LoginValidate
    {
        $this->field = [
            'mobile' => '手机号',
            'code'   => '验证码',
        ];

        return $this->only(['mobile', 'code', 'sign'])
            ->append('mobile', 'require|mobile|min:11|max:11')
            ->append('code', 'require|alphaDash|max:200')
            ->append('sign', 'require|alphaDash|max:200');
    }

    /**
     * URL验证
     *
     * @return LoginValidate
     * @author windy
     */
    public function sceneUrl(): LoginValidate
    {
        return $this->only(['url'])
            ->append('url', 'require|url|max:800');
    }

    /**
     * 注册账号
     *
     * @return LoginValidate
     * @author windy
     */
    public function sceneRegister(): LoginValidate
    {
        $this->field = [
            'account'  => '账号',
            'password' => '密码',
            'mobile'   => '手机号',
            'code'     => '验证码',
        ];
        return $this->only(['account', 'password', 'mobile', 'code'])
            ->append('account', 'require|alphaNum|min:2|max:20')
            ->append('password', 'require|alphaNum|min:6|max:20')
            ->append('mobile', 'require|mobile|min:11|max:11')
            ->append('code', 'require|alphaDash|max:6');
    }

    /**
     * 修改密码

     * @return LoginValidate
     * @author windy
     */
    public function sceneChangePwd(): LoginValidate
    {
        $this->field = [
            'newPassword' => '新密码',
            'oldPassword' => '原密码'
        ];
        return $this->only(['newPassword', 'oldPassword'])
            ->append('newPassword', 'require|alphaNum|min:6|max:20')
            ->append('oldPassword', 'require|alphaNum|min:6|max:20');
    }

    /**
     * 忘记密码

     * @return LoginValidate
     * @author windy
     */
    public function sceneForgetPwd(): LoginValidate
    {
        $this->field = [
            'mobile'   => '手机号',
            'code'     => '验证码',
            'password' => '新密码'
        ];
        return $this->only(['password', 'mobile', 'code'])
            ->append('password', 'require|alphaNum|min:6|max:20')
            ->append('mobile', 'require|mobile|min:11|max:11')
            ->append('code', 'require|alphaDash|max:6');
    }

    /**
     * 绑定微信

     * @return LoginValidate
     * @author windy
     */
    public function sceneBindWeChat(): LoginValidate
    {
        return $this->only(['code'])
            ->append('code', 'require|alphaDash');
    }

    /**
     * 绑定手机

     * @return LoginValidate
     * @author windy
     */
    public function sceneBindMobile(): LoginValidate
    {
        return $this->only(['mobile', 'code'])
            ->append('mobile', 'require|mobile|min:11|max:11')
            ->append('code', 'require|alphaDash|max:6');
    }

    /**
     * 绑定邮箱

     * @return LoginValidate
     * @author windy
     */
    public function sceneBindEmail(): LoginValidate
    {
        return $this->only(['mobile', 'code'])
            ->append('email', 'require|email')
            ->append('code', 'require|alphaDash|max:6');
    }
}