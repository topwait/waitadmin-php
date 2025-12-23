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
 * 登录参数验证器
 */
class LoginValidate extends Validate
{
    /**
     * 场景验证
     *
     * @return LoginValidate
     * @author zero
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
     * @author zero
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
     * @author zero
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
     * UniApp微信登录
     *
     * @return LoginValidate
     * @author zero
     */
    public function sceneUni(): LoginValidate
    {
        return $this->only(['code', 'wxCode'])
            ->append('openid', 'require')
            ->append('access_token', 'require');
    }

    /**
     * 微信登录
     *
     * @return LoginValidate
     * @author zero
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
     * @author zero
     */
    public function sceneOa(): LoginValidate
    {
        return $this->only(['code', 'state'])
            ->append('code', 'require|min:32')
            ->append('state', 'require|min:32');
    }

    /**
     * 绑定登录
     *
     * @return LoginValidate
     * @author zero
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
     * @author zero
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
     * @author zero
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
            ->append('mobile', 'mobile|min:11|max:11')
            ->append('code', 'alphaDash|max:6');
    }
}