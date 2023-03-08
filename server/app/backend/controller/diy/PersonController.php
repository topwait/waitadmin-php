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

namespace app\backend\controller\diy;

use app\backend\service\diy\PersonService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

/**
 * 个人中心装饰管理
 */
class PersonController extends Backend
{
    /**
     * 功能列表
     *
     * @return View|Json
     * @author windy
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            $list = PersonService::lists();
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 功能新增
     *
     * @return Json|View
     * @author windy
     */
    public function add(): View|Json
    {
        if ($this->isAjaxPost()) {
            PersonService::add($this->request->post());
            return AjaxUtils::success();
        }

        return view();
    }

    /**
     * 功能编辑
     *
     * @return Json|View
     * @author windy
     */
    public function edit(): View|Json
    {
        if ($this->isAjaxPost()) {
            PersonService::edit($this->request->post());
            return AjaxUtils::success();
        }

        $id = intval($this->request->get('id'));

        return view('', [
            'detail' => PersonService::detail($id)
        ]);
    }

    /**
     * 功能删除
     *
     * @return Json
     * @author windy
     */
    public function del(): Json
    {
        if ($this->isAjaxPost()) {
            $id = intval($this->request->post('id'));
            PersonService::del($id);
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}