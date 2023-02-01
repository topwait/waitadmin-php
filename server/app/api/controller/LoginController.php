<?php

namespace app\api\controller;

use app\api\service\LoginService;
use app\api\validate\LoginValidate;
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
        $post     = $this->request->post();
        $terminal = intval($post['terminal']??1);
        $validate = new LoginValidate();

        $response = [];
        switch ($post['scene']) {
            case 'account':
                $validate->goCheck('account');
                $response = LoginService::accountLogin($post['account'], $post['password']);
                break;
            case 'mobile':
                $validate->goCheck('mobile');
                $response = LoginService::mobileLogin($post['mobile'], $post['code']);
                break;
            case 'wx':
                $validate->goCheck('wx');
                $response = LoginService::wxLogin($post['code'], $terminal);
                break;
            case 'oa':
                break;
        }

        return AjaxUtils::success('', $response);
    }
}