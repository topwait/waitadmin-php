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
declare (strict_types = 1);

namespace app\backend\service\system;

use app\common\basics\Service;
use app\common\exception\OperateException;
use app\common\model\sys\SysDictData;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 字典数据服务类
 */
class DictDataService extends Service
{
    /**
     * 字典数据列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author zero
     */
    public static function lists(array $get): array
    {
        $modelSysDictData = new SysDictData();
        $lists = $modelSysDictData
            ->field(['id,name,value,sort,remark,is_enable'])
            ->where(['type_id'=>intval($get['type_id'])])
            ->where(['is_delete'=>0])
            ->order('sort desc, id desc')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 10,
                'var_page'  => 'page'
            ])->toArray();

        foreach ($lists['data'] as &$item) {
            $item['remark'] = $item['remark'] ?: '-';
        }

        return ['count'=>$lists['total'], 'list'=>$lists['data']] ?? [];
    }

    /**
     * 字典数据详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function detail(int $id): array
    {
        $modelSysDictData = new SysDictData();
        return $modelSysDictData
            ->field(['id,name,value,sort,remark,is_enable'])
            ->where(['id'=>$id])
            ->where(['is_delete'=>0])
            ->findOrFail()
            ->toArray();
    }

    /**
     * 字典数据新增
     *
     * @param array $post
     * @throws OperateException
     * @author zero
     */
    public static function add(array $post): void
    {
        $modelSysDictData = new SysDictData();
        $modelSysDictData->checkDataAlreadyExist([
            ['type_id', '=', intval($post['type_id'])],
            ['value', '=', $post['value']],
            ['is_delete', '=', 0],
        ], '改字典数据值已存在!');

        SysDictData::create([
            'type_id'   => $post['type_id'],
            'name'      => $post['name'],
            'value'     => $post['value'],
            'sort'      => $post['sort']??0,
            'remark'    => $post['remark']??'',
            'is_enable' => $post['is_enable'],
        ]);
    }

    /**
     * 字典数据编辑
     *
     * @param array $post
     * @throws OperateException
     * @author zero
     */
    public static function edit(array $post): void
    {
        $modelSysDictData = new SysDictData();
        $modelSysDictData->checkDataAlreadyExist([
            ['id', '<>', intval($post['id'])],
            ['type_id', '=', intval($post['type_id'])],
            ['value', '=', $post['value']],
            ['is_delete', '=', 0],
        ], '改字典数据值已存在!');

        SysDictData::update([
            'name'        => $post['name'],
            'value'       => $post['value'],
            'sort'        => $post['sort']??0,
            'remark'      => $post['remark']??'',
            'is_enable'   => $post['is_enable'],
            'update_time' => time(),
        ], ['id'=>intval($post['id'])]);
    }

    /**
     * 字典数据删除
     *
     * @param array $ids
     * @author zero
     */
    public static function del(array $ids): void
    {
        SysDictData::update([
            'is_enable'   => 0,
            'is_delete'   => 1,
            'delete_time' => time()
        ], array(['id', 'in', $ids]));
    }
}