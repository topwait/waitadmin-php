<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/WaitAdmin
// | github:  https://github.com/topwait/waitadmin
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\frontend\validate;

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

        $rule    = 'require|mobile|min:11|max:11';
        $value   = request()->post('mobile');
        $pattern = '/^1[3456789]\d{9}$/';
        if (!preg_match($pattern, $value)) {
            $this->field['mobile'] = '邮箱号';
            $rule = 'require|email';
        }

        return $this->only(['newPassword', 'mobile', 'code'])
            ->append('newPassword', 'require|alphaDash|min:6|max:20')
            ->append('mobile', $rule)
            ->append('code', 'require|alphaDash|max:6');
    }

    /**
     * 修改密码
     *
     * @return UserValidate
     * @author zero
     */
    public function sceneChangePwd(): UserValidate
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

    /**
     * 绑定邮箱
     *
     * @return UserValidate
     * @author zero
     */
    public function sceneBindEmail(): UserValidate
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
     * 绑定手机
     *
     * @return UserValidate
     * @author zero
     */
    public function sceneBindMobile(): UserValidate
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
}