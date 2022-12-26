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

namespace addons\curd\validate;


use app\common\basics\Validate;

/**
 * 代码生成参数验证器
 *
 * Class GenValidate
 * @package addons\curd\validate
 */
class GenValidate extends Validate
{
    protected $rule = [
        'id'            => 'require|posInteger',
        'table_name'    => 'require|max:200',
        'table_comment' => 'require|max:200',
        'table_alias'   => 'max:100',
        'author'        => 'max:100',
        'tpl_type'      => 'require|in:curd,tree',
        'gen_type'      => 'require|in:down,code',
        'gen_module'    => 'require|max:100',
        'gen_folder'    => 'max:100',
        'menu_type'     => 'require|in:auto,hand',
        'menu_pid'      => 'number',
        'menu_name'     => 'max:100',
        'menu_icon'     => 'max:100',
        'join_status'   => 'require|in:0,1',
        'join'          => 'array|checkJoin',
        'cols'          => 'array|checkCols'
    ];

    public function __construct()
    {
        $this->field = [
            'table_name'    => '表名称',
            'table_comment' => '表描述',
            'table_alias'   => '表别名',
            'author'        => '作者姓名',
            'tpl_type'      => '模板类型',
            'gen_type'      => '生成方式',
            'gen_module'    => '生成模块',
            'gen_folder'    => '生成目录',
            'menu_type'     => '构建菜单',
            'menu_pid'      => '菜单父级',
            'menu_name'     => '菜单名称',
            'menu_icon'     => '菜单图标',
            'join_status'   => '连表状态',
            'join'          => '子表关联',
            'cols'          => '表格字段',
        ];

        parent::__construct();
    }

    /**
     * 验证关联参数
     *
     * @param $value
     * @param $rule
     * @param array $data
     * @return bool|string
     * @author windy
     */
    protected function checkJoin($value, $rule, array $data=[]): bool|string
    {
        unset($rule);
        if (!$data['join_status']) {
            return true;
        }

        foreach ($value as $item) {
            if (!in_array($item['join_type'], ['inner', 'left', 'right'])) {
                return '关联类型不在合法范围: [inner, left, right]';
            }
            if (strlen(trim($item['join_alias'])) > 20) {
                return '子表别名不能超出20个字符';
            }
            if (!trim($item['sub_table'])) {
                return '请选择关联的子表';
            }
            if (!trim($item['primary_key'])) {
                return '关联主键不能为空';
            }
            if (!trim($item['foreign_key'])) {
                return '关联外键不能为空';
            }
        }
        return true;
    }

    /**
     * 验证表的字段
     *
     * @param $value
     * @return bool|string
     * @author windy
     */
    protected function checkCols($value): bool|string
    {
        foreach ($value as $item) {
            if (strlen(trim($item['column_comment'])) > 200) {
                return '字段描述不能大于200个字符';
            }
        }
        return true;
    }
}