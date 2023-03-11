<?php

namespace app\api\controller;


use app\api\service\DiyService;
use app\api\service\UserService;
use app\common\utils\AjaxUtils;
use think\response\Json;

class DiyController
{
    protected array $notNeedLogin = ['index', 'tie', 'me'];

    public function index()
    {

    }

    /**
     * 联系客服装修
     *
     * @return Json
     * @author windy
     */
    public function tie(): Json
    {
        $result = DiyService::tie();
        return AjaxUtils::success($result);
    }

    /**
     * 个人中心装修
     *
     * @return Json
     * @author windy
     */
    public function me(): Json
    {
        $result = DiyService::me();
        return AjaxUtils::success($result);
    }
}