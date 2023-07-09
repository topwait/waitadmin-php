<?php

namespace app\backend\controller\diy;

use app\backend\service\diy\ThemeService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

/**
 * 主题风格管理
 */
class ThemeController extends Backend
{
    /**
     * 主题配置页面
     *
     * @return View
     * @method [GET]
     * @author zero
     */
    public function index(): View
    {
        return view('', [
            'detail' => ThemeService::detail()
        ]);
    }

    /**
     * 主题配置保存
     *
     * @return Json
     * @method [POST]
     * @author zero
     */
    public function save(): Json
    {
        if ($this->isAjaxPost()) {
            $post = $this->request->post();
            ThemeService::save($post);
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}