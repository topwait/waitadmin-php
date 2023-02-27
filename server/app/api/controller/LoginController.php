<?php

namespace app\api\controller;

use app\api\service\LoginService;
use app\api\validate\LoginValidate;
use app\common\basics\Api;
use app\common\exception\OperateException;
use app\common\utils\AjaxUtils;
use Exception;
use think\response\Json;

/**
 * 登录管理
 */
class LoginController extends Api
{
    protected array $notNeedLogin = ['register', 'login', 'oaCodeUrl', 'forgetPwd'];

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

        $validate->goCheck('scene');

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
                $phoneCode = $post['wxCode']??'';
                $response = LoginService::wxLogin($post['code'], $phoneCode, $this->terminal);
                break;
            case 'oa':
                $validate->goCheck('oa');
                $response = LoginService::oaLogin($post['code'], $this->terminal);
                break;
            case 'ba':
                $validate->goCheck('ba');
                $sign = $post['sign'] ?? '';
                $response = LoginService::bindLogin($post['mobile'], $post['code'], $sign,  $this->terminal);
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
     * @throws Exception
     * @author windy
     */
    public function oaCodeUrl(): Json
    {
        (new LoginValidate())->goCheck('url');
        $url = $this->request->get('url');

        $response = LoginService::oaCodeUrl($url);
        return AjaxUtils::success($response);
    }

    /**
     * 修改密码
     *
     * @return Json
     * @throws OperateException
     * @author windy
     */
    public function changePwd(): Json
    {
        (new LoginValidate())->goCheck('changePwd');

        LoginService::changePwd($this->request->post(), $this->userId);
        return AjaxUtils::success();
    }

    /**
     * 忘记密码
     *
     * @return Json
     * @throws OperateException
     * @author windy
     */
    public function forgetPwd(): Json
    {
        (new LoginValidate())->goCheck('forgetPwd');

        LoginService::forgetPwd($this->request->post());
        return AjaxUtils::success();
    }

    /**
     * 绑定微信
     *
     * @return Json
     * @throws OperateException
     * @author windy
     */
    public function bindWeChat(): Json
    {
        (new LoginValidate())->goCheck('bindWeChat');

        LoginService::bindWeChat($this->request->post(), $this->userId);
        return AjaxUtils::success();
    }

    /**
     * 绑定手机
     *
     * @return Json
     * @throws OperateException
     * @author windy
     */
    public function bindMobile(): Json
    {
        (new LoginValidate())->goCheck('bindMobile');

        LoginService::bindMobile($this->request->post(), $this->userId);
        return AjaxUtils::success();
    }

    /**
     * 绑定邮箱
     *
     * @return Json
     * @throws OperateException
     * @author windy
     */
    public function bindEmail(): Json
    {
        (new LoginValidate())->goCheck('bindEmail');

        LoginService::bindEmail($this->request->post(), $this->userId);
        return AjaxUtils::success();
    }
}