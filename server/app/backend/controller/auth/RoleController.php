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

namespace app\backend\controller\auth;

use app\backend\service\auth\MenuService;
use app\backend\service\auth\RoleService;
use app\backend\validate\auth\RoleValidate;
use app\backend\validate\PageValidate;
use app\common\basics\Backend;
use app\common\exception\OperateException;
use app\common\utils\AjaxUtils;
use app\common\utils\ArrayUtils;
use Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 角色管理
 */
class RoleController extends Backend
{
    /**
     * 角色列表
     *
     * @return Json|View
     * @throws DbException
     * @method [GET]
     * @author zero
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            (new PageValidate())->goCheck();
            $list = RoleService::lists($this->request->get());
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 角色新增
     *
     * @return Json|View
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws Exception
     * @method [GET|POST]
     * @author zero
     */
    public function add(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new RoleValidate())->addCheck();
            RoleService::add($this->request->post());
            return AjaxUtils::success();
        }

        $treeMenu = ArrayUtils::toTreeJson(MenuService::lists());

        return view('', [
            'treeMenu' => json_encode($treeMenu)
        ]);
    }

    /**
     * 角色编辑
     *
     * @return Json|View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws OperateException
     * @method [GET|POST]
     * @author zero
     */
    public function edit(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new RoleValidate())->editCheck();
            RoleService::edit($this->request->post());
            return AjaxUtils::success();
        }

        (new RoleValidate())->idCheck();
        $id       = intval($this->request->get('id'));
        $detail   = RoleService::detail($id);
        $checked  = RoleService::checked($detail['menu_ids']);
        $treeMenu = ArrayUtils::toTreeJson(MenuService::lists());

        return view('', [
            'detail'   => $detail,
            'checked'  => json_encode($checked),
            'treeMenu' => json_encode($treeMenu)
        ]);
    }

    /**
     * 角色删除
     *
     * @return Json
     * @throws DbException
     * @throws OperateException
     * @method [POST]
     * @author zero
     */
    public function del(): Json
    {
        if ($this->isAjaxPost()) {
            (new RoleValidate())->idsCheck();
            RoleService::del($this->request->post('ids'));
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}