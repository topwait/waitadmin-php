<?php

namespace app\backend\controller\diy;

use app\backend\service\diy\TabbarService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

/**
 * 底部导航装饰
 */
class TabbarController extends Backend
{
    /**
     * 底部导航页面
     *
     * @return View|Json
     * @author windy
     */
    public function index(): View|Json
    {
        $result = TabbarService::detail();
        if ($this->isAjaxGet()) {
            return AjaxUtils::success($result['list']);
        }

        return view('', $result);
    }

    /**
     * 底部导航配置
     *
     * @return Json
     * @author windy
     */
    public function save(): Json
    {
        if ($this->isAjaxPost()) {
            TabbarService::save($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}