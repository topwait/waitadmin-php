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

namespace app\frontend\controller;

use app\common\basics\Frontend;
use app\common\exception\OperateException;
use app\common\service\msg\MsgDriver;
use app\common\utils\AjaxUtils;
use app\common\utils\ConfigUtils;
use app\frontend\service\ArticleService;
use app\frontend\service\IndexService;
use think\facade\Cookie;
use think\response\Json;
use think\response\View;

/**
 * 主页管理
 */
class IndexController extends Frontend
{
    protected array $notNeedLogin = ['test', 'index', 'protocol', 'sendSms', 'sendEmail'];

    /**
     * 首页
     *
     * @return View
     * @method [GET]
     * @author zero
     */
    public function index(): View
    {
        $logon = Cookie::get('logon', '0');
        Cookie::delete('logon');

        return view('', [
            'logon'    => intval($logon),
            'links'    => IndexService::getLinks(),
            'banner'   => IndexService::getBanner(1),
            'adv'      => IndexService::getBanner(2),
            'topping'  => ArticleService::recommend('topping', 6),
            'everyday' => ArticleService::recommend('everyday', 8),
            'lately'   => ArticleService::recommend('lately', 8),
            'ranking'  => ArticleService::recommend('ranking', 8)
        ]);
    }

    /**
     * 网站协议
     *
     * @return View
     * @method [GET]
     * @throws OperateException
     * @author zero
     */
    public function protocol(): View
    {
        $type  = $this->request->get('type', '');
        $value = ConfigUtils::get('policy', $type);
        if ($value === null) {
            throw new OperateException('协议不存在!');
        }

        $array = ['service'=>'服务协议', 'privacy'=>'隐私协议'];
        return view('', [
            'title'   => $array[$type],
            'content' => $value
        ]);
    }

    /**
     * 发送短信
     *
     * @return Json
     * @method [POST]
     * @author zero
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
     * @method [POST]
     * @author zero
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