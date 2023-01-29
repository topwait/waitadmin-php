<?php

namespace app\api\controller;

use app\api\service\LoginService;
use app\common\basics\Api;
use app\common\utils\AjaxUtils;
use Exception;
use think\response\Json;

class LoginController extends Api
{
    /**
     * 登录
     *
     * @return Json
     * @throws Exception
     * @author windy
     */
    public function login(): Json
    {
        $post = $this->request->post();
        $terminal = intval($post['terminal']);

        switch ($post['scene']) {
            case 'wx':
                LoginService::wxLogin($post['code'], $terminal);
                break;
        }

        return AjaxUtils::success();
    }
}