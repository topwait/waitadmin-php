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

namespace addons\curd\model;

use app\common\basics\Models;

/**
 * 生成表模型
 */
class GenTable extends Models
{
    // 设置字段信息
    protected $schema = [
        'id'              => 'int',     //主键
        'table_name'      => 'string',  //表名称
        'table_engine'    => 'string',  //表引擎
        'table_comment'   => 'string',  //表描述
        'table_alias'     => 'string',  //表别名
        'author'          => 'string',  //作者名
        'tpl_type'        => 'string',  //模板类型: [curd=单表, tree=树表]
        'gen_type'        => 'string',  //生成方式: [down=下载, code=覆盖]
        'gen_class'       => 'string',  //生成类名
        'gen_module'      => 'string',  //生成模块
        'gen_folder'      => 'string',  //生成目录
        'menu_type'       => 'string',  //菜单构建: [auto=自动, hand=手动]
        'menu_name'       => 'string',  //菜单图标
        'menu_icon'       => 'string',  //菜单图标
        'menu_pid'        => 'int',     //菜单父级
        'join_status'     => 'int',     //关联状态: [0=关闭, 1=开启]
        'join_array'      => 'string',  //关联内容
        'create_time'     => 'int',     //创建时间
        'update_time'     => 'int'      //更新时间
    ];

    /**
     * 获取器: 转为数组
     *
     * @param $value
     * @return array
     * @author zero
     */
    public function getJoinArrayAttr($value): array
    {
        if ($value) {
            return json_decode($value, true);
        }
        return [];
    }
}