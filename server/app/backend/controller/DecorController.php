<?php

namespace app\backend\controller;

use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

class DecorController extends Backend
{
    /**
     * Diy底部导航
     *
     * @return Json|View
     * @author windy
     */
    public function diyTabBar(): View|Json
    {
        if ($this->isAjaxPost()) {
            return AjaxUtils::success();
        }

        return view();
    }

    /**
     * Diy首页页面
     *
     * @return Json|View
     * @author windy
     */
    public function diyHome(): View|Json
    {
        if ($this->isAjaxPost()) {
            return AjaxUtils::success();
        }

        return view();
    }

    /**
     * Diy用户中心
     *
     * @return Json|View
     * @author windy
     */
    public function diyUser(): View|Json
    {
        if ($this->isAjaxPost()) {
            return AjaxUtils::success();
        }

        return view();
    }
}