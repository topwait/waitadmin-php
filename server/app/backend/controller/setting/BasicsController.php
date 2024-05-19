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

use app\backend\service\setting\BasicsService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use Exception;
use think\response\Json;
use think\response\View;

/**
 * 基础配置管理
 */
class BasicsController extends Backend
{
    /**
     * 基本配置详情
     *
     * @return View
     * @method [GET]
     * @author zero
     */
    public function index(): View
    {
        return view('setting/basics', BasicsService::detail());
    }

    /**
     * 基本配置保存
     *
     * @return Json
     * @throws Exception
     * @method [GET|POST]
     * @author zero
     */
    public function save(): Json
    {
        if ($this->isAjaxPost()) {
            BasicsService::save($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}