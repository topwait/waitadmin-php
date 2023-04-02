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

namespace app\backend\controller\diy;

use app\backend\service\diy\TabbarService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

/**
 * 底部导航装饰管理
 */
class TabbarController extends Backend
{
    /**
     * 底部导航页面
     *
     * @return View|Json
     * @method [GET]
     * @author windy
     */
    public function index(): View|Json
    {
        $result = TabbarService::detail();
        if ($this->isAjaxGet()) {
            return AjaxUtils::success($result['list']);
        }

        return view('', $result);
    }

    /**
     * 底部导航配置
     *
     * @return Json
     * @method [POST]
     * @author windy
     */
    public function save(): Json
    {
        if ($this->isAjaxPost()) {
            TabbarService::save($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}