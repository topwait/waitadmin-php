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
 * 菜单参数验证器
 *
 * Class MenuValidate
 * @package app\admin\validate\auth
 */
class MenuValidate extends Validate
{
    protected $rule = [
        'id'         => 'require|integer',
        'pid'        => 'require|number',
        'title'      => 'require|max:20',
        'perms'      => 'max:100',
        'icon'       => 'max:100',
        'sort'       => 'number|minValue:0',
        'is_menu'    => 'require|in:0,1',
        'is_disable' => 'require|in:0,1'
    ];

    public function __construct()
    {
        $this->field = [
            'pid'        => '上级菜单',
            'title'      => '菜单名称',
            'perms'      => '权限标识',
            'icon'       => '菜单图标',
            'sort'       => '菜单排序',
            'is_menu'    => '是否菜单',
            'is_disable' => '是否禁用'
        ];

        parent::__construct();
    }
}