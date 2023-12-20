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
use app\common\service\wechat\WeChatService;
use app\common\utils\AjaxUtils;
use app\frontend\cache\ScanLoginCache;
use app\frontend\service\UserService;
use app\frontend\validate\UserValidate;
use Exception;
use think\db\exception\DbException;
use think\response\Json;
use think\response\View;

/**
 * 用户管理
 */
class UserController extends Frontend
{
    protected array $notNeedLogin = ['bindWeChat'];

    /**
     * 个人中心
     *
     * @return View
     * @method [GET]
     * @author zero
     */
    public function index(): View
    {
        return view();
    }

    /**
     * 收藏管理
     *
     * @return View|Json
     * @throws DbException
     * @method [GET]
     * @author zero
     */
    public function collect(): View|Json
    {
        if ($this->isAjaxGet()) {
            $lists = UserService::collect($this->userId);
            return AjaxUtils::success($lists);
        }

        return view();
    }

    /**
     * 账号编辑
     *
     * @return Json
     * @method [POST]
     * @author zero
     */
    public function edit(): Json
    {
        if ($this->isAjaxPost()) {
            UserService::edit($this->request->post(), $this->userId);
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }

    /**
     * 账号绑定
     *
     * @return View|Json
     * @throws OperateException
     * @method [GET|POST]
     * @author zero
     */
    public function binding(): View|Json
    {
        if ($this->isAjaxPost()) {
            $post = $this->request->post();
            switch ($post['field']) {
                case 'changeAvatar':
                    UserService::changeAvatar($post, $this->userId);
                    break;
                case 'changePwd':
                    (new UserValidate())->goCheck('changePwd');
                    UserService::changePwd($post, $this->userId);
                    break;
                case 'bindMobile':
                    (new UserValidate())->goCheck('bindMobile');
                    UserService::bindMobile($post, $this->userId);
                    break;
                case 'bindEmail':
                    (new UserValidate())->goCheck('bindEmail');
                    UserService::bindEmail($post, $this->userId);
                    break;
            }
            return AjaxUtils::success();
        }

        return view('', [
            'field' => $get['field'] ?? '',
            'value' => $get['value'] ?? ''
        ]);
    }

    /**
     * 微信授权(链接/绑定微信用)
     *
     * @return View|Json
     * @method [GET|POST]
     * @throws Exception
     * @author zero
     */
    public function buildWxUrl(): View|Json
    {
        if ($this->isAjaxPost()) {
            $uniqId    = uniqid();
            $ip        = request()->ip();
            $microTime = microtime();
            $rand      = rand(1, 1100);
            $state     = md5($uniqId . $ip . $microTime . $rand);

            $event = 'bind';
            ScanLoginCache::set($state, ['status'=>ScanLoginCache::$ING, 'userId'=>$this->userId]);
            return AjaxUtils::success(WeChatService::oaBuildQrCode($state, $event));
        }

        return view('wx');
    }

    /**
     * 绑定微信
     *
     * @throws Exception
     * @method [GET]
     * @author zero
     */
    public function bindWeChat()
    {
        $code  = $this->request->get('code', '');
        $state = $this->request->get('state', '');
        UserService::bindWeChat($code, $state);

        $this->redirect('/');
    }

    /**
     * 检测微信绑定
     *
     * @return Json
     * @method [GET]
     * @author zero
     */
    public function ticketBindWx(): Json
    {
        $key = $this->request->get('key', '');
        if (!$key) {
            return AjaxUtils::error('缺失参数key');
        }

        $response = UserService::ticketBindWx($key);
        return AjaxUtils::success($response);
    }
}