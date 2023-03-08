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
use app\backend\validate\auth\MenuValidate;
use app\common\basics\Backend;
use app\common\exception\OperateException;
use app\common\utils\AjaxUtils;
use app\common\utils\ArrayUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 菜单管理
 */
class MenuController extends Backend
{
    /**
     * 菜单列表
     *
     * @return Json|View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            $list = MenuService::lists();
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 菜单新增
     *
     * @return Json|View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws OperateException
     * @author windy
     */
    public function add(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new MenuValidate())->addCheck();
            MenuService::add($this->request->post());
            return AjaxUtils::success();
        }

        return view('', [
            'menus' => ArrayUtils::toTreeHtml(MenuService::lists())
        ]);
    }

    /**
     * 菜单编辑
     *
     * @return Json|View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws OperateException
     * @author windy
     */
    public function edit(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new MenuValidate())->editCheck();
            MenuService::edit($this->request->post());
            return AjaxUtils::success();
        }

        (new MenuValidate())->idCheck();
        $id = intval($this->request->get('id'));

        return view('', [
            'detail' => MenuService::detail($id),
            'menus'  => ArrayUtils::toTreeHtml(MenuService::lists())
        ]);
    }

    /**
     * 菜单删除
     *
     * @return Json
     * @throws OperateException
     * @author windy
     */
    public function del(): Json
    {
        if ($this->isAjaxPost()) {
            (new MenuValidate())->idCheck();
            MenuService::del($this->request->post('id'));
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}