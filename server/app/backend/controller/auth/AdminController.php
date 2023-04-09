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

use app\backend\service\auth\AdminService;
use app\backend\service\auth\DeptService;
use app\backend\service\auth\PostService;
use app\backend\service\auth\RoleService;
use app\backend\validate\auth\AdminValidate;
use app\backend\validate\PageValidate;
use app\common\basics\Backend;
use app\common\exception\NotAuthException;
use app\common\exception\OperateException;
use app\common\utils\AjaxUtils;
use app\common\utils\ArrayUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 管理员管理
 */
class AdminController extends Backend
{
    /**
     * 管理员列表
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
            $list = AdminService::lists($this->request->get());
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 管理员信息
     *
     * @return Json|View
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @method [GET|POST]
     * @author zero
     */
    public function info(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new AdminValidate())->editCheck();
            AdminService::info($this->request->post(), $this->adminId);
            return AjaxUtils::success();
        }

        return view('', [
            'roles'  => RoleService::all(),
            'detail' => AdminService::detail($this->adminId)
        ]);
    }

    /**
     * 管理员新增
     *
     * @return Json|View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @method [GET|POST]
     * @author zero
     */
    public function add(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new AdminValidate())->addCheck();
            AdminService::add($this->request->post());
            return AjaxUtils::success();
        }

        return view('', [
            'dept'  => ArrayUtils::toTreeHtml(DeptService::lists()),
            'post'  => PostService::all(),
            'roles' => RoleService::all()
        ]);
    }

    /**
     * 管理员编辑
     *
     * @return Json|View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws NotAuthException
     * @throws OperateException
     * @method [GET|POST]
     * @author zero
     */
    public function edit(): Json|View
    {
        if ($this->isAjaxPost()) {
            (new AdminValidate())->editCheck();
            AdminService::edit($this->request->post(), $this->adminId);
            return AjaxUtils::success();
        }

        (new AdminValidate())->idCheck();
        $id = intval($this->request->get('id'));

        return view('', [
            'dept'   => ArrayUtils::toTreeHtml(DeptService::lists()),
            'post'   => PostService::all(),
            'roles'  => RoleService::all(),
            'detail' => AdminService::detail($id)
        ]);
    }

    /**
     * 管理员删除
     *
     * @return Json
     * @throws OperateException
     * @method [POST]
     * @author zero
     */
    public function del(): Json
    {
        if ($this->isAjaxPost()) {
            (new AdminValidate())->idsCheck();
            AdminService::del($this->request->post('ids'), $this->adminId);
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}