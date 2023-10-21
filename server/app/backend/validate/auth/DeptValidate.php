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
 * 部门-参数验证器
 */
class DeptValidate extends Validate
{
    protected $rule = [
        'id'         => 'require|integer',
        'pid'        => 'require|integer',
        'name'       => 'require|chsDash|unique:authDept',
        'duty'       => 'require|chsDash|max:30',
        'mobile'     => 'require|mobile',
        'sort'       => 'number|minValue:0|maxValue:99999',
        'is_disable' => 'require|in:0,1'
    ];

    public function __construct()
    {
        $this->field = [
            'pid'        => '上级部门',
            'name'       => '部门名称',
            'mobile'     => '部门电话',
            'duty'       => '负责人',
            'sort'       => '排序',
            'is_disable' => '状态'
        ];

        parent::__construct();
    }
}