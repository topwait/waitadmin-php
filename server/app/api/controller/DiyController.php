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

use app\api\service\DiyService;
use app\common\basics\Api;
use app\common\utils\AjaxUtils;
use think\response\Json;

/**
 * 装修管理
 */
class DiyController extends Api
{
    protected array $notNeedLogin = ['diy', 'tie', 'me'];

    /**
     * 首页页面装修
     *
     * @return Json
     * @method [GET]
     * @author zero
     */
    public function diy(): Json
    {
        $result = DiyService::index();
        return AjaxUtils::success($result);
    }

    /**
     * 联系客服装修
     *
     * @return Json
     * @method [GET]
     * @author zero
     */
    public function tie(): Json
    {
        $result = DiyService::tie();
        return AjaxUtils::success($result);
    }

    /**
     * 个人中心装修
     *
     * @return Json
     * @method [GET]
     * @author zero
     */
    public function me(): Json
    {
        $result = DiyService::me();
        return AjaxUtils::success($result);
    }
}