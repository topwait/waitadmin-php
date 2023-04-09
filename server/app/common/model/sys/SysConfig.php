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

namespace app\common\model\sys;

use app\common\basics\Models;

/**
 * 系统配置模型
 */
class SysConfig extends Models
{
    // 设置字段信息
    protected $schema = [
        'id'          => 'int',     //主键
        'type'        => 'string',  //类型
        'key'         => 'string',  //键名
        'value'       => 'string',  //键值
        'remarks'     => 'string',  //备注
        'create_time' => 'int',     //创建时间
        'update_time' => 'int'      //更新时间
    ];
}