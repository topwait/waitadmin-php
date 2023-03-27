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
use app\frontend\service\UserService;
use think\response\Json;
use think\response\View;

/**
 * 用户管理
 */
class UserController extends Frontend
{
    /**
     * 个人中心
     *
     * @return View
     * @author windy
     */
    public function index(): View
    {
        return view('', [
            'detail' => UserService::info($this->userId)
        ]);
    }

    /**
     * 账号管理
     *
     * @return View
     * @author windy
     */
    public function account(): View
    {
        return view('', [
            'detail' => UserService::info($this->userId)
        ]);
    }

    /**
     * 收藏管理
     *
     * @return View
     * @author windy
     */
    public function collect(): View
    {
        return view();
    }

    /**
     * 账号更新
     *
     * @return Json
     * @author windy
     */
    public function update(): Json
    {
        if ($this->isAjaxPost()) {
            UserService::update($this->request->post(), $this->userId);
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }

    /**
     * 账号绑定
     *
     * @return View|Json
     * @throws OperateException
     * @author windy
     */
    public function binding(): View|Json
    {
        if ($this->isAjaxPost()) {
            $post = $this->request->post();
            switch ($post['field']) {
                case 'avatar':
                    UserService::changeAvatar($post, $this->userId);
                    break;
                case 'password':
                    UserService::changePwd($post, $this->userId);
                    break;
                case 'mobile':
                    UserService::bindMobile($post, $this->userId);
                    break;
                case 'email':
                    UserService::bindEmail($post, $this->userId);
                    break;
            }
            return AjaxUtils::success();
        }

        $get = $this->request->get();
        if (!empty($get['code']) && !empty($get['state'])) {
            UserService::bindWeChat($get, $this->userId);
            return AjaxUtils::success();
        }

        return view('', [
            'field' => $get['field'] ?? '',
            'value' => $get['value'] ?? ''
        ]);
    }


}