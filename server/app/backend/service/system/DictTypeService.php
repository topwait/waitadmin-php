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

namespace app\backend\service\system;

use app\common\basics\Service;
use app\common\exception\OperateException;
use app\common\model\sys\SysDictType;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 字典类型服务类
 */
class DictTypeService extends Service
{
    /**
     * 字典类型列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author zero
     */
    public static function lists(array $get): array
    {
        self::setSearch([
            '%like%'   => ['name', 'type'],
            '='        => ['is_enable'],
            'datetime' => ['datetime@create_time']
        ]);

        $modelSysDictType = new SysDictType();
        $lists = $modelSysDictType
            ->field(['id,name,type,remark,is_enable,create_time'])
            ->where(self::$searchWhere)
            ->where(['is_delete'=>0])
            ->order('id desc')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page'  => 'page'
            ])->toArray();

        return ['count'=>$lists['total'], 'list'=>$lists['data']] ?? [];
    }

    /**
     * 字典类型详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function detail(int $id): array
    {
        $modelSysDictType = new SysDictType();
        return $modelSysDictType
            ->field(['id,name,type,remark,is_enable'])
            ->where(['id'=>$id])
            ->where(['is_delete'=>0])
            ->findOrFail()
            ->toArray();
    }

    /**
     * 字典类型新增
     *
     * @param array $post
     * @throws OperateException
     * @author zero
     */
    public static function add(array $post): void
    {
        $modelSysDictType = new SysDictType();
        $modelSysDictType->checkDataAlreadyExist([
            ['name', '=', $post['name']],
            ['type', '=', $post['type']],
            ['is_delete', '=', 0]
        ], '该字典类型已存在!');

        SysDictType::create([
            'name'      => $post['name'],
            'type'      => $post['type'],
            'remark'    => $post['remark']??'',
            'is_enable' => $post['is_enable']
        ]);
    }

    /**
     * 字典类型编辑
     *
     * @param array $post
     * @throws OperateException
     * @author zero
     */
    public static function edit(array $post): void
    {
        $modelSysDictType = new SysDictType();
        $modelSysDictType->checkDataAlreadyExist([
            ['id', '<>', intval($post['id'])],
            ['name', '=', $post['name']],
            ['type', '=', $post['type']],
            ['is_delete', '=', 0]
        ], '该字典类型已存在!');

        SysDictType::update([
            'name'        => $post['name'],
            'type'        => $post['type'],
            'remark'      => $post['remark']??'',
            'is_enable'   => $post['is_enable'],
            'update_time' => time(),
        ], ['id'=>intval($post['id'])]);
    }

    /**
     * 字典类型删除
     *
     * @param array $ids
     * @author zero
     */
    public static function del(array $ids): void
    {
        SysDictType::update([
            'is_enable'   => 0,
            'is_delete'   => 1,
            'delete_time' => time()
        ], array(['id', 'in', $ids]));
    }
}