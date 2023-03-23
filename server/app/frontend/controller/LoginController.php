<?php

namespace app\frontend\controller;

use app\backend\validate\LoginValidate;
use app\common\basics\Frontend;
use app\common\exception\OperateException;
use app\common\utils\AjaxUtils;
use app\frontend\service\LoginService;
use app\frontend\validate\RegisterValidate;
use think\response\Json;
use think\response\View;

class LoginController extends Frontend
{
    protected array $notNeedLogin = ['login', 'register'];

    /**
     * 注册账号
     *
     * @return Json|View
     * @author windy
     */
    public function register(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new RegisterValidate())->goCheck();
            LoginService::register($this->request->post());
            return AjaxUtils::success('注册成功');
        }

        return view();
    }

    /**
     * 登录系统
     *
     * @return Json|View
     * @throws OperateException
     * @author windy
     */
    public function login(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new LoginValidate())->goCheck();
            LoginService::login($this->request->post());
            return AjaxUtils::success('登录成功');
        }

        return view();
    }

    public function forget(): View|Json
    {
        if ($this->isAjaxPost()) {
            return AjaxUtils::success('重置成功');
        }

        return view();
    }
}