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

namespace app\backend\validate\system;


use app\common\basics\Validate;

/**
 * 计划任务参数验证器
 *
 * Class CrontabValidate
 * @package app\admin\validate\system
 */
class CrontabValidate extends Validate
{
    protected $rule = [
        'id'      => 'require|integer',
        'name'    => 'require|chsAlphaNum',
        'command' => 'require|max:64',
        'params'  => 'max:64',
        'rules'   => 'max:64',
        'remarks' => 'max:255',
        'status'  => 'require|in:0,1,2,3'
    ];

    public function __construct()
    {
        $this->field = [
            'name'    => '任务名称',
            'command' => '执行命令',
            'params'  => '附带参数',
            'rules'   => '执行规则',
            'remarks' => '备注信息',
            'status'  => '运行状态'
        ];

        parent::__construct();
    }
}