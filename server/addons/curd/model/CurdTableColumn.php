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
// | Author: zero <2474369941@qq.com>
// +----------------------------------------------------------------------

namespace addons\curd\model;

use app\common\basics\Models;

/**
 * 生成表字段模型
 */
class CurdTableColumn extends Models
{
    protected $name = 'addons_curd_table_column';

    protected $schema = [
        'id'             => 'int',     //主键
        'table_id'       => 'int',     //表外键
        'column_name'    => 'string',  //列名称
        'column_comment' => 'string',  //列描述
        'column_length'  => 'string',  //列长度
        'column_type'    => 'string',  //列类型
        'model_type'     => 'string',  //模型类型
        'is_pk'          => 'int',     //是否主键: [0=否, 1=是]
        'is_increment'   => 'int',     //是否自增: [0=否, 1=是]
        'is_insert'      => 'int',     //是否插入: [0=否, 1=是]
        'is_edit'        => 'int',     //是否编辑: [0=否, 1=是]
        'is_list'        => 'int',     //是否列表: [0=否, 1=是]
        'is_query'       => 'int',     //是否列表: [0=否, 1=是]
        'query_type'     => 'string',  //查询条件
        'html_type'      => 'string',  //显示类型
        'create_time'    => 'int',     //创建时间
        'update_time'    => 'int'      //更新时间
    ];
}