<?php

namespace app\api\controller;

use app\api\service\UsersService;
use app\common\basics\Api;
use app\common\utils\AjaxUtils;
use think\response\Json;

class UsersController extends Api
{
    protected array $notNeedLogin = [];

    /**
     * 个人中心
     *
     * @return Json
     * @author windy
     */
    public function center(): Json
    {
        $result = UsersService::center($this->userId);
        return AjaxUtils::success($result);
    }

    /**
     * 个人信息
     *
     * @return Json
     * @author windy
     */
    public function info(): Json
    {
        $result = UsersService::info($this->userId);
        return AjaxUtils::success($result);
    }

    public function edit()
    {

    }
}