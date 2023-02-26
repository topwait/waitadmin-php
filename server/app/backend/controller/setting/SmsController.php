<?php

namespace app\backend\controller\setting;

use app\backend\service\setting\SmsService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

class SmsController extends Backend
{
    /**
     * 短信引擎列表
     *
     * @return View|Json
     * @author windy
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            $list = SmsService::lists();
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 短信引擎配置
     *
     * @return View|Json
     * @author windy
     */
    public function save(): View|Json
    {
        if ($this->isAjaxPost()) {
            SmsService::save($this->request->post());
            return AjaxUtils::success();
        }

        $alias = $this->request->get('alias');
        return view('setting/sms/'.$alias, [
            'detail' => SmsService::detail($alias),
        ]);
    }
}