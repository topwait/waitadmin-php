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
declare (strict_types = 1);

namespace app\backend\validate\auth;

use app\common\basics\Validate;

/**
 * 岗位参数验证器
 */
class PostValidate extends Validate
{
    protected $rule = [
        'id'         => 'require|integer',
        'ids'        => 'require|array',
        'code'       => 'require|max:30|alphaNum|unique:authPost',
        'name'       => 'require|max:30|chsAlphaNum|unique:authPost',
        'sort'       => 'number|minValue:0|maxValue:99999',
        'remarks'    => 'max:200',
        'is_disable' => 'require|in:0,1'
    ];

    public function __construct()
    {
        $this->field = [
            'code'       => '岗位编码',
            'name'       => '岗位名称',
            'sort'       => '岗位排序',
            'remarks'    => '岗位备注',
            'is_disable' => '岗位状态'
        ];

        parent::__construct();
    }
}