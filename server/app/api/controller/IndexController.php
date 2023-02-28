<?php

namespace app\api\controller;

use app\api\service\IndexService;
use app\common\basics\Api;
use app\common\service\msg\MsgDriver;
use app\common\utils\AjaxUtils;
use think\response\Json;

/**
 * 主页管理
 */
class IndexController extends Api
{
    protected array $notNeedLogin = ['config', 'sendSms'];

    /**
     * 全局配置
     *
     * @return Json
     * @author windy
     */
    public function config(): Json
    {
        $detail = IndexService::config();
        return AjaxUtils::success($detail);
    }

    /**
     * 协议政策
     *
     * @return Json
     * @author windy
     */
    public function policy(): Json
    {
        $detail = IndexService::policy();
        return AjaxUtils::success($detail);
    }

    /**
     * 发送短信
     *
     * @return Json
     * @author windy
     */
    public function sendSms(): Json
    {
        $scene  = $this->request->post('scene');
        $mobile = $this->request->post('mobile');

        MsgDriver::send(intval($scene), [
            'mobile' => $mobile,
            'code'   => make_rand_code(null, '', 6)
        ]);

        return AjaxUtils::success();
    }

    /**
     * 发送邮件
     *
     * @return Json
     * @author windy
     */
    public function sendEmail(): Json
    {
        $scene = $this->request->post('scene');
        $email = $this->request->post('email');

        MsgDriver::send(intval($scene), [
            'email' => $email,
            'code'  => make_rand_code(null, '', 6)
        ]);

        return AjaxUtils::success();
    }
}