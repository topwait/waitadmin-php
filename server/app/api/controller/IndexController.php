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

use app\api\service\IndexService;
use app\common\basics\Api;
use app\common\service\msg\MsgDriver;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;

/**
 * 主页管理
 */
class IndexController extends Api
{
    protected array $notNeedLogin = ['index', 'config', 'sendSms', 'sendEmail'];

    /**
     * 首页数据
     *
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function index(): Json
    {
        $detail = IndexService::index();
        return AjaxUtils::success($detail);
    }

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
        $type = $this->request->get('type');
        $detail = IndexService::policy($type);
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