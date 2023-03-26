<?php

namespace app\frontend\validate;

use app\common\basics\Validate;

/**
 * 用户参数验证器
 */
class UserValidate extends Validate
{
    /**
     * 邮箱绑定
     *
     * @return UserValidate
     * @author windy
     */
    public function sceneEmail(): UserValidate
    {
        $this->field = [
            'field'  => '字段',
            'email'  => '邮箱',
            'code'   => '验证码',
        ];
        return $this->only(['field', 'email', 'code'])
            ->append('field', 'require|in:email')
            ->append('email', 'require|email')
            ->append('code', 'require|alphaDash|max:6');
    }

    /**
     * 手机绑定
     *
     * @return UserValidate
     * @author windy
     */
    public function sceneMobile(): UserValidate
    {
        $this->field = [
            'field'  => '字段',
            'mobile' => '手机',
            'code'   => '验证码',
        ];
        return $this->only(['field', 'mobile', 'code'])
            ->append('field', 'require|in:mobile')
            ->append('mobile', 'require|mobile')
            ->append('code', 'require|alphaDash|max:6');
    }

    /**
     * 密码修改
     *
     * @return UserValidate
     * @author windy
     */
    public function scenePassword(): UserValidate
    {
        $this->field = [
            'field'       => '字段',
            'newPassword' => '新密码',
            'oldPassword' => '旧密码',
        ];
        return $this->only(['field', 'email', 'code'])
            ->append('field', 'require|in:password')
            ->append('newPassword', 'require|alphaDash|min:6|max:20')
            ->append('oldPassword', 'require|alphaDash|min:6|max:20');
    }

}