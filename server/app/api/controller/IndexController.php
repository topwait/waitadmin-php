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
    protected array $notNeedLogin = ['config', 'sendSms'];

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

    /**
     * 协议政策
     *
     * @return Json
     * @author windy
     */
    public function policy(): Json
    {
        $detail = IndexService::policy();
        return AjaxUtils::success($detail);
    }

    /**
     * 发送短信
     *
     * @return Json
     * @author windy
     */
    public function sendSms(): Json
    {
        return AjaxUtils::success();
    }
}