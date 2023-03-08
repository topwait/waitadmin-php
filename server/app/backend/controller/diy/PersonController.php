<?php

namespace app\backend\controller\diy;

use app\backend\service\diy\PersonService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

/**
 * 个人中心装饰
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