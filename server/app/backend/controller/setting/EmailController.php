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

namespace app\backend\controller\setting;

use app\backend\service\setting\EmailService;
use app\common\basics\Backend;
use app\common\exception\SystemException;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

/**
 * 邮件配置管理
 */
class EmailController extends Backend
{
    /**
     * 邮件配置详情
     *
     * @return View
     * @method [GET]
     * @author windy
     */
    public function index(): View
    {
        return view('setting/email', [
            'detail' => EmailService::detail()
        ]);
    }

    /**
     * 邮件配置保存
     *
     * @return Json
     * @method [POST]
     * @author windy
     */
    public function save(): Json
    {
        if ($this->isAjaxPost()) {
            EmailService::save($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }

    /**
     * 邮件测试发送
     *
     * @return Json
     * @throws SystemException
     * @method [POST]
     * @author windy
     */
    public function test(): Json
    {
        if ($this->isAjaxPost()) {
            $post = $this->request->post();
            $this->validate($post, ['recipient'=>'require|email']);
            EmailService::testEmail($post['recipient']);
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}