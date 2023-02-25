<?php

namespace app\backend\controller\setting;

use app\backend\service\setting\LoginService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

class LoginController extends Backend
{
    /**
     * 登录配置详情
     *
     * @return View
     * @author windy
     */
    public function index(): View
    {
        return view('setting/login', [
            'detail' => LoginService::detail()
        ]);
    }

    /**
     * 登录配置保存
     *
     * @return Json
     * @author windy
     */
    public function save(): Json
    {
        if ($this->isAjaxPost()) {
            LoginService::save($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}