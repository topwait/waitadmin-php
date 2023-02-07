<?php

namespace app\api\controller;

use app\api\service\IndexService;
use app\api\widgets\TokenWidget;
use app\common\basics\Api;
use app\common\utils\AjaxUtils;
use think\response\Json;

/**
 * 主页管理
 */
class IndexController extends Api
{
    protected array $notNeedLogin = ['config'];

    /**
     * 全局配置
     *
     * @return Json
     * @author windy
     */
    public function config(): Json
    {
        TokenWidget::login(1);

        $detail = IndexService::config();
        return AjaxUtils::success($detail);
    }
}