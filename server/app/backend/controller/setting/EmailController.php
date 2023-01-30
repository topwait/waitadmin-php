<?php

namespace app\backend\controller\setting;

use app\backend\service\setting\BasicsService;
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
     * @author windy
     */
    public function save(): Json
    {
        if ($this->isAjaxPost()) {
            BasicsService::save($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }

    /**
     * 邮件测试发送
     *
     * @return Json
     * @throws SystemException
     * @author windy
     */
    public function test(): Json
    {
        if ($this->isAjaxPost()) {
            $post = $this->request->post();
            $this->validate($post, ['recipient'=>'require|email']);
            BasicsService::testEmail($post['recipient']);
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}