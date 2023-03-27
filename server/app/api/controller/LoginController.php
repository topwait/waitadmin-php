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

namespace app\api\controller;

use app\api\service\LoginService;
use app\api\validate\LoginValidate;
use app\common\basics\Api;
use app\common\utils\AjaxUtils;
use Exception;
use think\response\Json;

/**
 * 登录管理
 */
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
                $response = LoginService::baLogin($post['mobile'], $post['code'], $sign, $this->terminal);
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
     * 公众号链接
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
}