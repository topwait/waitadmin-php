<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/waitadmin-php
// | github:  https://github.com/topwait/waitadmin-php
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------

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
     * @author zero
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
     * @author zero
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
     * @author zero
     */
    public function sceneBindWeChat(): UserValidate
    {
        return $this->only(['code'])
            ->append('code', 'require|alphaDash');
    }

    /**
     * 绑定手机

     * @return UserValidate
     * @author zero
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
     * @author zero
     */
    public function sceneBindEmail(): UserValidate
    {
        return $this->only(['mobile', 'code'])
            ->append('email', 'require|email')
            ->append('code', 'require|alphaDash|max:6');
    }
}