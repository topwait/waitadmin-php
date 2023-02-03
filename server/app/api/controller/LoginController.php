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
    protected array $notNeedLogin = ['register', 'login'];

    /**
     * 注册
     *
     * @return Json
     * @throws Exception
     * @author windy
     */
    public function register(): Json
    {
        LoginService::register($this->request->post());
        return AjaxUtils::success();
    }

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
        $validate = new LoginValidate();

        $response = [];
        switch ($post['scene']) {
            case 'account':
                $validate->goCheck('account');
                $response = LoginService::accountLogin($post['account'], $post['password'], $this->terminal);
                break;
            case 'mobile':
                $validate->goCheck('mobile');
                $response = LoginService::mobileLogin($post['mobile'], $post['code']);
                break;
            case 'wx':
                $validate->goCheck('wx');
                $response = LoginService::wxLogin($post['code'], $this->terminal);
                break;
            case 'oa':
                break;
        }

        return AjaxUtils::success('', $response);
    }
}