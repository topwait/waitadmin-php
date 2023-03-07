<?php

namespace app\backend\controller\decor;

use app\backend\service\decor\DiyTabbarService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

/**
 * 底部导航装饰
 */
class DiyTabbarController extends Backend
{
    /**
     * 底部导航页面
     *
     * @return View|Json
     * @author windy
     */
    public function index(): View|Json
    {
        $result = DiyTabbarService::detail();
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
            DiyTabbarService::save($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}