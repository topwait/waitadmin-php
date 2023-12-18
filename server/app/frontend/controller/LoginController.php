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

use app\common\basics\Frontend;
use app\common\exception\OperateException;
use app\common\utils\AjaxUtils;
use app\frontend\service\LoginService;
use app\frontend\validate\LoginValidate;
use Exception;
use think\response\Json;
use think\response\View;

/**
 * 登录管理
 */
class LoginController extends Frontend
{
    protected array $notNeedLogin = ['index', 'login', 'register', 'pcQrCodeUrl', 'ticketByUser', 'forgetPwd'];

    /**
     * 弹出页面
     *
     * @return View
     * @method [GET]
     * @author zero
     */
    public function index(): View
    {
        $get = $this->request->get();
        return view('', [
            'scene' => $get['scene']??'login'
        ]);
    }

    /**
     * 注册账号
     *
     * @return Json|View
     * @throws OperateException
     * @method [POST]
     * @author zero
     */
    public function register(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new LoginValidate())->goCheck('register');

            LoginService::register($this->request->post(), $this->terminal);
            return AjaxUtils::success('注册成功');
        }

        return view();
    }

    /**
     * 登录系统
     *
     * @return Json|View
     * @throws OperateException
     * @throws Exception
     * @method [POST]
     * @author zero
     */
    public function login(): View|Json
    {
        if ($this->isAjaxPost()) {
            $post     = $this->request->post();
            $validate = new LoginValidate();
            $validate->goCheck('scene');

            switch ($post['scene']) {
                case 'account':
                    $validate->goCheck('account');
                    LoginService::accountLogin($post['account'], $post['password']);
                    break;
                case 'mobile':
                    $validate->goCheck('mobile');
                    LoginService::mobileLogin($post['mobile'], $post['code']);
                    break;
                case 'ba':
                    $validate->goCheck('ba');
                    $sign = $post['sign'] ?? '';
                    LoginService::baLogin(strval($post['mobile']), $post['code'], $sign, $this->terminal);
                    break;
                case 'op':
                    $validate->goCheck('op');
                    LoginService::opLogin($post['code'], $post['state'], $this->terminal);
            }

            return AjaxUtils::success('登录成功');
        }

        return view();
    }

    /**
     * 退出登录
     *
     * @method [GET]
     * @author zero
     */
    public function logout()
    {
        session('userId', null);
        $this->redirect(route('index/index'), 302);
    }

    /**
     * PC微信链接
     *
     * @return Json
     * @throws Exception
     * @method [GET]
     * @author zero
     */
    public function pcQrCodeUrl(): Json
    {
        $response = LoginService::pcQrCodeUrl();
        return AjaxUtils::success($response);
    }

    /**
     * PC微信扫码检测
     *
     * @return Json
     * @throws Exception
     * @method [GET]
     * @author zero
     */
    public function ticketByUser(): Json
    {
        $key = $this->request->get('key', '');
        if (!$key) {
            return AjaxUtils::error('缺失参数key');
        }

        $response = LoginService::ticketByUser($key);
        return AjaxUtils::success($response);
    }

    /**
     * 忘记密码
     *
     * @return Json
     * @throws OperateException
     * @method [POST]
     * @author zero
     */
    public function forgetPwd(): Json
    {
        if ($this->isAjaxPost()) {
            (new LoginValidate())->goCheck('forgetPwd');

            LoginService::forgetPwd($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}