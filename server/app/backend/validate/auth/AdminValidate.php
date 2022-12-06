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

namespace app\backend\validate\auth;


use app\common\basics\Validate;

/**
 * 管理员参数验证器
 *
 * Class AdminValidate
 * @package app\admin\validate\auth
 */
class AdminValidate extends Validate
{
    protected $rule = [
        'id'         => 'require|integer',
        'ids'        => 'require|array',
        'role_id'    => 'require|number',
        'dept_id'    => 'number',
        'post_id'    => 'number',
        'nickname'   => 'require|min:2|max:8|chsAlphaNum|unique:authAdmin',
        'username'   => 'require|min:2|max:8|chsAlphaNum|unique:authAdmin',
        'password'   => 'requireWith:password|min:6|max:18',
        'email'      => 'email|unique:authAdmin',
        'phone'      => 'mobile|unique:authAdmin',
        'is_disable' => 'require|in:0,1'
    ];

    public function __construct()
    {
        $this->field = [
            'role_id'    => '所属角色',
            'dept_id'    => '所属部门',
            'post_id'    => '所属岗位',
            'nickname'   => '用户昵称',
            'username'   => '登录账号',
            'password'   => '登录密码',
            'email'      => '电子邮箱',
            'phone'      => '手机号码',
            'is_disable' => '状态'
        ];

        parent::__construct();
    }
}