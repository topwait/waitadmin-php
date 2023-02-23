<?php

namespace app\backend\controller\setting;

use app\backend\service\setting\NoticeService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 通知设置
 */
class NoticeController extends Backend
{
    /**
     * 场景通知列表
     *
     * @return Json|View
     * @throws DbException
     * @author windy
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            $list = NoticeService::lists($this->request->get());
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 场景通知详情
     *
     * @return View|Json
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author windy
     */
    public function edit(): View|Json
    {
        if ($this->isAjaxPost()) {
            NoticeService::edit($this->request->post());
            return AjaxUtils::success();
        }

        $id = intval($this->request->get('id'));
        return view('', [
            'detail' => NoticeService::detail($id)
        ]);
    }
}