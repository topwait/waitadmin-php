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

namespace app\backend\validate\content;


use app\common\basics\Validate;

/**
 * 文章类目参数验证器
 */
class CategoryValidate extends Validate
{
    protected $rule = [
        'id'         => 'require|integer',
        'name'       => 'require',
        'sort'       => 'number',
        'is_disable' => 'require|in:0,1'
    ];

    public function __construct()
    {
        $this->field = [
            'title'      => '名称',
            'sort'       => '排序',
            'is_disable' => '状态'
        ];

        parent::__construct();
    }
}