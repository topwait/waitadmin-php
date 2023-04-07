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

namespace app\backend\service\setting\pc;

use app\common\basics\Service;
use app\common\model\DevNavigation;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 导航服务类
 */
class NavigationService extends Service
{
    /**
     * 导航列表
     *
     * @return array
     * @throws DbException
     * @author zero
     */
    public static function lists(): array
    {
        $model = new DevNavigation();
        return $model
            ->withoutField('is_delete,delete_time')
            ->where(['is_delete'=>0])
            ->order('sort desc, id desc')
            ->select()->toArray();
    }

    /**
     * 导航详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function detail(int $id): array
    {
        $model = new DevNavigation();
        return $model->withoutField('is_delete,delete_time')
            ->where(['id'=>intval($id)])
            ->where(['is_delete'=>0])
            ->findOrFail()
            ->toArray();
    }

    /**
     * 导航新增
     *
     * @param array $post
     * @author zero
     */
    public static function add(array $post): void
    {
        DevNavigation::create([
            'pid'         => $post['pid'],
            'name'        => $post['name'],
            'target'      => $post['target'] ?? '_self',
            'url'         => $post['url'] ?? '',
            'sort'        => $post['sort'] ?? 0,
            'is_disable'  => $post['is_disable'],
            'is_delete'   => 0,
            'create_time' => time(),
            'update_time' => time()
        ]);
    }

    /**
     * 导航编辑
     *
     * @param array $post
     * @author zero
     */
    public static function edit(array $post)
    {
        DevNavigation::update([
            'pid'         => $post['pid'],
            'name'        => $post['name'],
            'target'      => $post['target'] ?? '_self',
            'url'         => $post['url'] ?? '',
            'sort'        => $post['sort'] ?? 0,
            'is_disable'  => $post['is_disable'],
            'update_time' => time()
        ], ['id'=>intval($post['id'])]);
    }

    /**
     * 导航删除
     *
     * @param int $id
     * @author zero
     */
    public static function del(int $id)
    {
        DevNavigation::update([
            'is_delete'   => 1,
            'delete_time' => time()
        ], ['id'=>intval($id)]);
    }
}