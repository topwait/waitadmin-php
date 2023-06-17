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

namespace app\backend\service\user;

use app\common\basics\Service;
use app\common\exception\OperateException;
use app\common\model\user\UserGroup;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 用户分组服务类
 */
class GroupService extends Service
{
    /**
     * 所有分组
     *
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function all(): array
    {
        $model = new UserGroup();
        return $model->field('id,name')
            ->where(['is_delete'=>0])
            ->order('sort desc, id desc')
            ->select()
            ->toArray();
    }

    /**
     * 分组列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author zero
     */
    public static function lists(array $get): array
    {
        self::setSearch([
            '%like%' => ['name']
        ]);

        $model = new UserGroup();
        $lists = $model->field('id,name,remarks,sort,create_time')
            ->where(self::$searchWhere)
            ->where(['is_delete'=>0])
            ->order('sort desc, id desc')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page'  => 'page'
            ])->toArray();

        return ['count'=>$lists['total'], 'list'=>$lists['data']] ?? [];
    }

    /**
     * 分组详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function detail(int $id): array
    {
        $model = new UserGroup();
        return $model->field('id,name,remarks,sort')
            ->where(['id'=>$id])
            ->where(['is_delete'=>0])
            ->findOrFail()
            ->toArray();
    }

    /**
     * 分组新增
     *
     * @param array $post
     * @author zero
     */
    public static function add(array $post): void
    {
        UserGroup::create([
            'name'        => $post['name'],
            'remarks'     => $post['remarks'] ?? '',
            'sort'        => $post['sort'] ?? 0,
            'is_delete'   => 0,
            'create_time' => time(),
            'update_time' => time()
        ]);
    }

    /**
     * 分组编辑
     *
     * @param array $post
     * @throws OperateException
     * @author zero
     */
    public static function edit(array $post): void
    {
        $modelUserGroup = new UserGroup();
        $modelUserGroup->checkDataDoesNotExist(['id'=>intval($post['id']), 'is_delete'=>0]);

        UserGroup::update([
            'name'        => $post['name'],
            'remarks'     => $post['remarks'] ?? '',
            'sort'        => $post['sort'] ?? 0,
            'update_time' => time()
        ], ['id'=>intval($post['id'])]);
    }

    /**
     * 分组删除
     *
     * @param int $id
     * @throws OperateException
     * @author zero
     */
    public static function del(int $id)
    {
        $modelUserGroup = new UserGroup();
        $modelUserGroup->checkDataDoesNotExist(['id'=>$id, 'is_delete'=>0]);

        UserGroup::update([
            'is_delete'   => 1,
            'update_time' => time()
        ], ['id'=>$id]);
    }
}