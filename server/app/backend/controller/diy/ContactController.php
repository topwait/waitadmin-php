<?php

namespace app\backend\controller\diy;

use app\backend\service\diy\ContactService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

/**
 * 客服装修管理
 */
class ContactController extends Backend
{
    /**
     * 客服装修详情
     *
     * @return View
     * @method [GET]
     * @author zero
     */
    public function index(): View
    {
        $detail = ContactService::detail();
        return view('', [
            'detail' => $detail,
            'jsonp' => json_encode($detail),
        ]);
    }

    /**
     * 客服装修保存
     *
     * @return Json
     * @method [POST]
     * @author zero
     */
    public function save(): Json
    {
        if ($this->isAjaxPost()) {
            ContactService::save($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}