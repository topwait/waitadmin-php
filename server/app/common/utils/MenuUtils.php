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

namespace app\common\utils;


use app\common\model\auth\AuthMenu;
use app\common\model\auth\AuthPerm;
use Exception;

/**
 * 插件菜单工具
 *
 * Class MenuUtils
 * @package app\common\utils
 */
class MenuUtils
{
    /**
     * 创建插件菜单
     *
     * @param array $menus
     * @param int $pid
     * @throws Exception
     */
    public static function create(array $menus, int $pid=0): void
    {
        if (!empty($menus)) {
            $model = new AuthMenu();
            $model->startTrans();
            try {
                // 验证菜单变动
                $changeStatus = false;
                self::isChangeMenu($menus, $pid, $menus[0]['name'], $changeStatus);

                // 移除旧的菜单
                if ($changeStatus) {
                    $oldIds = $model->where('module', $menus[0]['name'])->column('id');
                    $perms = new AuthPerm();
                    $perms->whereIn('menu_id', $oldIds)->delete();
                    $model->where('module', $menus[0]['name'])->delete();
                }

                // 创建新的菜单
                if ($changeStatus) {
                    self::upgrade($menus, $pid, $menus[0]['name']);
                }

                $model->commit();
            } catch (Exception $e) {
                $model->rollback();
                throw new Exception($e->getMessage());
            }
        }
    }

    /**
     * 删除插件菜单
     *
     * @param string $name (插件名称)
     * @throws Exception
     */
    public static function delete(string $name): void
    {
        $model = new AuthMenu();
        $model->startTrans();
        try {
            $oldIds = $model->where('module', $name)->column('id');
            $perms = new AuthPerm();
            $perms->whereIn('menu_id', $oldIds)->delete();
            $model->where('module', $name)->delete();

            $model->commit();
        } catch (Exception $e) {
            $model->rollback();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 启用插件菜单
     *
     * @param string $name (插件名称)
     * @author windy
     */
    public static function enable(string $name): void
    {
        $model = new AuthMenu();
        $model->where(['module'=>$name])->update([
            'is_disable'  => 0,
            'update_time' => time()
        ]);
    }

    /**
     * 禁用插件菜单
     *
     * @param string $name (插件名称)
     * @author windy
     */
    public static function disable(string $name): void
    {
        $model = new AuthMenu();
        $model->where(['module'=>$name])->update([
            'is_disable'   => 1,
            'update_time' => time()
        ]);
    }

    /**
     * 升级插件菜单
     *
     * @param array $menus    (菜单)
     * @param int $pid        (父级)
     * @param string $module  (模块)
     * @throws Exception
     * @author windy
     */
    private static function upgrade(array $menus, int $pid=0, string $module='addon'): void
    {
        $model = new AuthMenu();
        foreach ($menus as $menu) {
            $menu['pid']    = $pid;
            $menu['module'] = $module;
            $menu['perms']  = trim($menu['perms'], '/');

            $authMenu = $model->where(['pid'=>$pid, 'module'=>$module, 'perms'=>$menu['perms']])->findOrEmpty();
            if (!$authMenu->isEmpty()) {
                $menu['id']  = $authMenu['id'];
                $menu['pid'] = $authMenu['pid'];
                $menu['delete_time'] = 0;
                $menu['is_delete']   = 0;
                AuthMenu::update($menu, ['id'=>$authMenu['id']]);
            } else {
                $authMenu = AuthMenu::create($menu);
            }

            if (!empty($menu['children'])) {
                self::upgrade($menu['children'], intval($authMenu['id']), $module);
            }
        }
    }

    /**
     * 验证菜单变动
     *
     * @param array $menus   (菜单)
     * @param int $pid       (父级)
     * @param string $module (模块)
     * @param false $changeStatus (变动状态: true=有变动, false=无变动)
     * @author windy
     */
    private static function isChangeMenu(array $menus, int $pid=0, string $module='addon', bool &$changeStatus=false): void
    {
        if (!$changeStatus) {
            $model = new AuthMenu();
            foreach ($menus as $menu) {
                $menu['pid'] = $pid;
                $menu['module'] = $module;
                $menu['perms'] = trim($menu['perms'], '/');

                $authMenu = $model->where(['pid' => $pid, 'module' => $module, 'perms' => $menu['perms']])->findOrEmpty();
                if ($authMenu->isEmpty()) {
                    $changeStatus = true;
                }

                if (!empty($menu['children'])) {
                    self::isChangeMenu($menu['children'], $authMenu['id']??0, $module, $changeStatus);
                }
            }
        }
    }
}