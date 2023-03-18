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

namespace app\backend\service\auth;

use app\common\basics\Service;
use app\common\exception\OperateException;
use app\common\model\auth\AuthMenu;
use app\common\utils\ArrayUtils;
use app\common\utils\ConfigUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Config;

/**
 * 菜单服务类
 */
class MenuService extends Service
{
    /**
     * 菜单列表
     *
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    public static function lists(): array
    {
        $model = new AuthMenu();
        return $model
            ->withoutField('is_delete,delete_time')
            ->where(['is_delete'=>0])
            ->order('sort asc, id asc')
            ->select()->toArray();
    }

    /**
     * 菜单详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author windy
     */
    public static function detail(int $id): array
    {
        $model = new AuthMenu();
        return $model
            ->withoutField('is_delete,delete_time')
            ->where(['id'=>intval($id)])
            ->where(['is_delete'=>0])
            ->findOrFail()
            ->toArray();
    }

    /**
     * 菜单新增
     *
     * @param array $post
     * @throws OperateException
     * @author windy
     */
    public static function add(array $post): void
    {
        if (intval($post['pid']) > 0) {
            $model = new AuthMenu();
            $model->checkDataDoesNotExist([
                'is_delete' => 0,
                'id' => intval($post['pid'])
            ], '父级菜单已不存在!');
        }

        AuthMenu::create([
            'pid'         => $post['pid'],
            'title'       => $post['title'],
            'icon'        => $post['icon'],
            'perms'       => $post['perms'],
            'sort'        => $post['sort'],
            'is_menu'     => $post['is_menu'],
            'is_disable'  => $post['is_disable'],
            'is_delete'   => 0,
            'create_time' => time(),
            'update_time' => time()
        ]);
    }

    /**
     * 菜单编辑
     *
     * @param array $post
     * @throws OperateException
     * @author windy
     */
    public static function edit(array $post): void
    {
        if ($post['id'] == $post['pid']) {
            throw new OperateException('父级菜单不能是自身!');
        }

        if (intval($post['pid']) > 0) {
            $model = new AuthMenu();
            $model->checkDataDoesNotExist([
                ['id', '=', intval($post['pid'])],
                ['is_delete', '=', 0]
            ], '父级菜单已不存在!');
        }

        $emptyIcon = 'layui-icon layui-icon-circle-dot';
        if (empty($post['icon']) || $post['icon'] == $emptyIcon) {
            $post['icon'] = '';
        }

        AuthMenu::update([
            'pid'         => $post['pid'],
            'title'       => $post['title'],
            'icon'        => $post['icon'],
            'perms'       => $post['perms'],
            'sort'        => $post['sort'],
            'is_menu'     => $post['is_menu'],
            'is_disable'  => $post['is_disable'],
            'update_time' => time(),
        ], ['id'=>intval($post['id'])]);
    }

    /**
     * 菜单删除
     *
     * @param int $id
     * @throws OperateException
     * @author windy
     */
    public static function del(int $id): void
    {
        $model = new AuthMenu();
        $model->checkDataDoesNotExist();
        $model->checkDataAlreadyExist([
            ['pid', '=', intval($id)],
            ['is_delete', '=', 0]
        ], '请先删除子级菜单再操作!');

        AuthMenu::update([
            'is_delete'   => 1,
            'delete_time' => time(),
        ], ['id' => intval($id)]);
    }

}