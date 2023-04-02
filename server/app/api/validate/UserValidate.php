<?php

namespace app\api\validate;

use app\common\basics\Validate;

/**
 * 用户参数验证器
 */
class UserValidate extends Validate
{
    /**
     * 忘记密码

     * @return UserValidate
     * @author windy
     */
    public function sceneForgetPwd(): UserValidate
    {
        $this->field = [
            'mobile'      => '手机号',
            'code'        => '验证码',
            'newPassword' => '新密码'
        ];
        return $this->only(['newPassword', 'mobile', 'code'])
            ->append('newPassword', 'require|alphaDash|min:6|max:20')
            ->append('mobile', 'require|mobile|min:11|max:11')
            ->append('code', 'require|alphaDash|max:6');
    }

    /**
     * 修改密码

     * @return UserValidate
     * @author windy
     */
    public function sceneChangePwd(): UserValidate
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
     * 绑定微信

     * @return UserValidate
     * @author windy
     */
    public function sceneBindWeChat(): UserValidate
    {
        return $this->only(['code'])
            ->append('code', 'require|alphaDash');
    }

    /**
     * 绑定手机

     * @return UserValidate
     * @author windy
     */
    public function sceneBindMobile(): UserValidate
    {
        $type = request()->post('type')??'';
        if ($type === 'code') {
            return $this->only(['code', 'type'])
                ->append('code', 'require|alphaDash|max:200')
                ->append('type', 'require|in:change,bind,code');
        } else {
            return $this->only(['mobile', 'code', 'type'])
                ->append('mobile', 'require|mobile|min:11|max:11')
                ->append('code', 'require|alphaDash|max:6')
                ->append('type', 'require|in:change,bind,code');
        }
    }

    /**
     * 绑定邮箱

     * @return UserValidate
     * @author windy
     */
    public function sceneBindEmail(): UserValidate
    {
        return $this->only(['mobile', 'code'])
            ->append('email', 'require|email')
            ->append('code', 'require|alphaDash|max:6');
    }
}