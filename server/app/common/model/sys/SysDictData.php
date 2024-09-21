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

namespace app\common\model\sys;

use app\common\basics\Models;

/**
 * 字典数据模型
 */
class SysDictData extends Models
{
// 设置字段信息
    protected $schema = [
        'id'          => 'int',     //主键
        'type_id'     => 'int',     //所属
        'name'        => 'string',  //键名
        'value'       => 'string',  //键值
        'remark'      => 'string',  //备注
        'is_enable'   => 'int',     //是否启用: [0=否, 1=是]
        'is_delete'   => 'int',     //是否删除: [0=否, 1=是
        'create_time' => 'int',     //创建时间
        'update_time' => 'int',     //更新时间
        'delete_time' => 'int'      //删除时间
    ];
}