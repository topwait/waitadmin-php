<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/WaitAdmin
// | github:  https://github.com/topwait/waitadmin
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\frontend\controller;

use app\backend\validate\LoginValidate;
use app\common\basics\Frontend;
use app\common\exception\OperateException;
use app\common\utils\AjaxUtils;
use app\frontend\service\LoginService;
use app\frontend\validate\RegisterValidate;
use think\response\Json;
use think\response\View;

/**
 * 登录管理
 */
class LoginController extends Frontend
{
    protected array $notNeedLogin = ['index', 'login', 'register'];

    public function index(): View
    {
        $get = $this->request->get();
        return view('', [
            'scene' => $get['scene']
        ]);
    }

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