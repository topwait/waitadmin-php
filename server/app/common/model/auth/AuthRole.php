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

namespace app\common\model\auth;

use app\common\basics\Models;

/**
 * 角色模型
 */
class AuthRole extends Models
{
    // 设置字段信息
    protected $schema = [
        'id'          => 'int',    // 角色主键
        'name'        => 'string', // 角色名称
        'describe'    => 'string', // 角色描述
        'sort'        => 'int',    // 角色排序
        'is_disable'  => 'int',    // 是否禁用: [0=否, 1=是]
        'is_delete'   => 'int',    // 是否删除: [0=否, 1=是]
        'create_time' => 'int',    // 创建时间
        'update_time' => 'int',    // 更新时间
        'delete_time' => 'int',    // 删除时间
    ];

}