<?php

namespace app\backend\controller\decor;

use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

class DiyUserController extends Backend
{
    /**
     * 个人中心装饰
     *
     * @return View|Json
     * @author windy
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            return AjaxUtils::success();
        }

        return view();
    }

    public static function save()
    {

    }
}