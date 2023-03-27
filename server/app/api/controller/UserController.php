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

use app\api\service\UserService;
use app\api\validate\UserValidate;
use app\common\basics\Api;
use app\common\exception\OperateException;
use app\common\utils\AjaxUtils;
use think\response\Json;

/**
 * 用户管理
 */
class UserController extends Api
{
    protected array $notNeedLogin = ['forgetPwd'];

    /**
     * 个人中心
     *
     * @return Json
     * @author windy
     */
    public function center(): Json
    {
        $result = UserService::center($this->userId);
        return AjaxUtils::success($result);
    }

    /**
     * 个人信息
     *
     * @return Json
     * @author windy
     */
    public function info(): Json
    {
        $result = UserService::info($this->userId);
        return AjaxUtils::success($result);
    }

    /**
     * 编辑信息
     *
     * @return Json
     * @throws OperateException
     * @author windy
     */
    public function edit(): Json
    {
        UserService::edit($this->request->post(), $this->userId);
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
        (new UserValidate())->goCheck('forgetPwd');

        UserService::forgetPwd($this->request->post());
        return AjaxUtils::success();
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
        (new UserValidate())->goCheck('changePwd');

        UserService::changePwd($this->request->post(), $this->userId);
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
        (new UserValidate())->goCheck('bindWeChat');

        UserService::bindWeChat($this->request->post(), $this->userId);
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
        (new UserValidate())->goCheck('bindMobile');

        UserService::bindMobile($this->request->post(), $this->userId);
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
        (new UserValidate())->goCheck('bindEmail');

        UserService::bindEmail($this->request->post(), $this->userId);
        return AjaxUtils::success();
    }
}