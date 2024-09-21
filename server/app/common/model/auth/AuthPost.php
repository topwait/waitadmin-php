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
 * 岗位模型
 */
class AuthPost extends Models
{
    // 设置字段信息
    protected $schema = [
        'id'          => 'int',     //岗位主键
        'code'        => 'string',  //岗位编号
        'name'        => 'string',  //岗位名称
        'remarks'     => 'string',  //岗位备注
        'sort'        => 'string',  //排序编号
        'is_disable'  => 'int',     //是否禁用: [0=否, 1=是]
        'is_delete'   => 'int',     //是否禁用: [0=否, 1=是]
        'create_time' => 'int',     //创建时间
        'update_time' => 'int',     //更新时间
        'delete_time' => 'int',     //删除时间
    ];
}