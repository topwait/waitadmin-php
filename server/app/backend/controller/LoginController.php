<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/waitadmin-php
// | github:  https://github.com/topwait/waitadmin-php
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\backend\controller;

use app\backend\service\LoginService;
use app\backend\validate\LoginValidate;
use app\common\basics\Backend;
use app\common\exception\OperateException;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

/**
 * 登录管理
 */
class LoginController extends Backend
{
    protected array $notNeedLogin = ['index', 'check'];
    protected array $notNeedPower = ['logout'];

    /**
     * 登录页面
     *
     * @return View
     * @method [GET]
     * @author zero
     */
    public function index(): View
    {
        $action = in_array(request()->action(), $this->notNeedLogin);
        if (session('adminUser') and !$action) {
            $this->redirect(route('index/index'), 302);
        }

        $entrance = config('project.backend_entrance');
        return view('common/login', ['entrance'=>$entrance]);
    }

    /**
     * 登录验证
     *
     * @return Json
     * @throws OperateException
     * @method [POST]
     * @author zero
     */
    public function check(): Json
    {
        if ($this->isAjaxPost()) {
            (new LoginValidate())->goCheck();
            LoginService::login($this->request->post());
            return AjaxUtils::success('登录成功');
        }

        return AjaxUtils::error();
    }

    /**
     * 退出系统
     *
     * @return Json
     * @method [POST]
     * @author zero
     */
    public function logout(): Json
    {
        session('adminUser', null);
        return AjaxUtils::success('退出成功');
    }
}