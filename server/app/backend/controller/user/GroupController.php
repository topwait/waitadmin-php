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

namespace app\backend\controller\user;

use app\backend\service\user\GroupService;
use app\backend\validate\PageValidate;
use app\backend\validate\user\GroupValidate;
use app\common\basics\Backend;
use app\common\exception\OperateException;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 用户分组管理
 */
class GroupController extends Backend
{
    /**
     * 分组列表
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
            $list = GroupService::lists($this->request->get());
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 分组新增
     *
     * @return Json|View
     * @method [GET|POST]
     * @author zero
     */
    public function add(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new GroupValidate())->addCheck();
            GroupService::add($this->request->post());
            return AjaxUtils::success();
        }

        return view();
    }

    /**
     * 分组编辑
     *
     * @return Json|View
     * @throws OperateException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @method [GET|POST]
     * @author zero
     */
    public function edit(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new GroupValidate())->editCheck();
            GroupService::edit($this->request->post());
            return AjaxUtils::success();
        }

        $id = intval($this->request->get('id'));
        return view('', [
            'detail' => GroupService::detail($id)
        ]);
    }

    /**
     * 分组删除
     *
     * @return Json
     * @throws OperateException
     * @method [POST]
     * @author zero
     */
    public function del(): Json
    {
        if ($this->isAjaxPost()) {
            (new GroupValidate())->idCheck();
            GroupService::del(intval($this->request->post('id')));
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}