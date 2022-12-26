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

namespace addons\curd;


use app\common\utils\MenuUtils;
use Exception;
use think\Addons;

class Plugin extends Addons
{
    /**
     * 插件名称
     * @var string
     */
    private string $module = 'curd';

    /**
     * 插件安装方法
     *
     * @throws Exception
     * @return bool
     * @author windy
     */
    public function install(): bool
    {
        $menus = [
            [
                'name'       => $this->module,
                'title'      => 'Curd',
                'perms'      => 'addons/curd/gen/index',
                'icon'       => 'layui-icon icon-member-user',
                'is_menu'    => 1,
                'is_disable' => 0,
                'children'   => [
                    ['title'=>'生成列表', 'perms'=>'addons/curd/gen/index', 'is_menu'=>0, 'is_disable'=>1],
                    ['title'=>'查看库表', 'perms'=>'addons/curd/gen/tables', 'is_menu'=>0, 'is_disable'=>1],
                    ['title'=>'更新库表', 'perms'=>'addons/curd/gen/update', 'is_menu'=>0, 'is_disable'=>1],
                    ['title'=>'销毁库表', 'perms'=>'addons/curd/gen/destroy', 'is_menu'=>0, 'is_disable'=>1],
                    ['title'=>'同步库表', 'perms'=>'addons/curd/gen/synchrony', 'is_menu'=>0, 'is_disable'=>1],
                    ['title'=>'导入库表', 'perms'=>'addons/curd/gen/imports', 'is_menu'=>0, 'is_disable'=>1],
                    ['title'=>'导出生成', 'perms'=>'addons/curd/gen/exports', 'is_menu'=>0, 'is_disable'=>1],
                    ['title'=>'下载生成', 'perms'=>'addons/curd/gen/download', 'is_menu'=>0, 'is_disable'=>1],
                    ['title'=>'预览生成', 'perms'=>'addons/curd/gen/preview', 'is_menu'=>0, 'is_disable'=>1]
                ]
            ]
        ];

        MenuUtils::create($menus);
        return true;
    }

    /**
     * 插件卸载方法
     *
     * @throws Exception
     * @return bool
     * @author windy
     */
    public function uninstall(): bool
    {
        MenuUtils::delete($this->module);
        return true;
    }

    /**
     * 插件启用方法
     *
     * @return bool
     * @throws Exception
     * @author windy
     */
    public function enabled(): bool
    {
        MenuUtils::enable($this->module);
        return true;
    }

    /**
     * 插件禁用方法
     *
     * @return bool
     * @author windy
     */
    public function disabled(): bool
    {
        MenuUtils::disable($this->module);
        return true;
    }
}