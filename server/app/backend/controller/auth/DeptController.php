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

namespace app\backend\controller\auth;

use app\backend\service\auth\DeptService;
use app\backend\validate\auth\DeptValidate;
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
 * 部门管理
 */
class DeptController extends Backend
{
    /**
     * 部门列表
     *
     * @return Json|View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @method [GET]
     * @author zero
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            $list = DeptService::lists();
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 部门新增
     *
     * @return Json|View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws OperateException
     * @method [GET|POST]
     * @author zero
     */
    public function add(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new DeptValidate())->addCheck();
            DeptService::add($this->request->post());
            return AjaxUtils::success();
        }

        return view('', [
            'dept' => ArrayUtils::toTreeHtml(DeptService::lists())
        ]);
    }

    /**
     * 部门编辑
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
            (new DeptValidate())->editCheck();
            DeptService::edit($this->request->post());
            return AjaxUtils::success();
        }

        (new DeptValidate())->idCheck();
        $id = intval($this->request->get('id'));

        return view('', [
            'detail' => DeptService::detail($id),
            'child'  => DeptService::child($id),
            'dept'   => ArrayUtils::toTreeHtml(DeptService::lists())
        ]);
    }

    /**
     * 部门删除
     *
     * @return Json
     * @throws OperateException
     * @method [POST]
     * @author zero
     */
    public function del(): Json
    {
        if ($this->isAjaxPost()) {
            (new DeptValidate())->idCheck();
            DeptService::del(intval($this->request->post('id')));
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}