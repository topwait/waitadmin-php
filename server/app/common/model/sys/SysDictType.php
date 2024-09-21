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
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\model\relation\HasMany;

/**
 * 字典类型模型
 */
class SysDictType extends Models
{
    // 设置字段信息
    protected $schema = [
        'id'          => 'int',     //主键
        'name'        => 'string',  //字典名称
        'type'        => 'string',  //字典类型
        'remark'      => 'string',  //字典备注
        'is_enable'   => 'int',     //是否启用: [0=否, 1=是]
        'is_delete'   => 'int',     //是否删除: [0=否, 1=是
        'create_time' => 'int',     //创建时间
        'update_time' => 'int',     //更新时间
        'delete_time' => 'int'      //删除时间
    ];

    /**
     * 一对多关联数据
     *
     * @return HasMany|SysDictType
     * @author zero
     */
    public function datas(): HasMany|SysDictType
    {
        return $this->hasMany(SysDictData::class, 'type_id', 'id')
            ->field('id,type_id,name,value')
            ->where(['is_enable'=>1])
            ->where(['is_delete'=>0]);
    }

    /**
     * 查询所有启用的字段
     *
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function queryAllEnable(): array
    {
        $model = new self();
        return $model
            ->field(['id','name','type'])
            ->where(['is_enable'=>1])
            ->where(['is_delete'=>0])
            ->order('id desc')
            ->select()
            ->toArray();
    }
}