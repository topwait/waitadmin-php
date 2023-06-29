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
 * 字典数据参数验证器
 */
class DictDataValidate extends Validate
{
    protected $rule = [
        'id'         => 'require|posInteger',
        'type_id'    => 'require|posInteger',
        'name'       => 'require|max:100',
        'value'      => 'require|max:200',
        'sort'       => 'require|integer',
        'remarks'    => 'max:200',
        'is_enable'  => 'require|in:0,1'
    ];

    public function __construct()
    {
        $this->field = [
            'type_id'   => '字典类型',
            'name'      => '数据标签',
            'value'     => '数据键值',
            'sort'      => '排序',
            'remarks'   => '备注',
            'is_enable' => '状态'
        ];

        parent::__construct();
    }
}