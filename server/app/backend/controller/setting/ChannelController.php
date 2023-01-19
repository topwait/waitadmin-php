<?php

namespace app\backend\controller\setting;

use app\backend\service\setting\ChannelService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

/**
 * 渠道配置管理
 */
class ChannelController extends Backend
{
    /**
     * 渠道配置信息
     *
     * @return View
     * @author windy
     */
    public function index(): View
    {
        return view('setting/channel', [
            'detail' => ChannelService::detail()
        ]);
    }

    /**
     * 渠道配置保存
     *
     * @return Json
     * @author windy
     */
    public function save(): Json
    {
        if ($this->isAjaxPost()) {
            ChannelService::save($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}