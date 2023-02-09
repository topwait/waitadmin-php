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
    protected array $notNeedLogin = ['register', 'login', 'oaCodeUrl'];

    /**
     * 注册
     *
     * @return Json
     * @throws Exception
     * @author windy
     */
    public function register(): Json
    {
        (new LoginValidate())->goCheck('register');

        $result = LoginService::register($this->request->post(), $this->terminal);
        return AjaxUtils::success($result);
    }

    /**
     * 忘记
     *
     * @return Json
     * @throws Exception
     * @author windy
     */
    public function forget(): Json
    {
        (new LoginValidate())->goCheck('forget');

        LoginService::forgetPwd($this->request->post());
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
                $response = LoginService::mobileLogin($post['mobile'], $post['code'], $this->terminal);
                break;
            case 'wx':
                $validate->goCheck('wx');
                $phoneCode = $post['phoneCode']??'';
                $response = LoginService::wxLogin($post['code'], $phoneCode, $this->terminal);
                break;
            case 'oa':
                break;
        }

        return AjaxUtils::success($response);
    }

    /**
     * 退出
     *
     * @return Json
     * @author windy
     */
    public function logout(): Json
    {
        return AjaxUtils::success();
    }

    /**
     * 公众号授权链接
     *
     * @return Json
     * @author windy
     */
    public function oaCodeUrl(): Json
    {
        $url = $this->request->get('url');
        $response = LoginService::oaCodeUrl($url);
        return AjaxUtils::success($response);
    }
}