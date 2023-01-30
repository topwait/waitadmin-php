<?php

namespace app\api\controller;

use app\api\service\IndexService;
use app\common\basics\Api;
use app\common\utils\AjaxUtils;
use think\response\Json;

/**
 * 主页管理
 */
class IndexController extends Api
{
    /**
     * 全局配置
     *
     * @return Json
     * @author windy
     */
    public function config(): Json
    {
        $detail = IndexService::config();
        return AjaxUtils::success($detail);
    }
}