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

namespace app\backend\controller\system;

use app\backend\service\system\ClearService;
use app\backend\validate\system\ClearValidate;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

/**
 * 系统缓存清除管理
 */
class ClearController extends Backend
{
    /**
     * 缓存清理页面
     *
     * @return View
     * @method [GET]
     * @author zero
     */
    public function index(): View
    {
        return view();
    }

    /**
     * 缓存清理操作
     *
     * @return Json
     * @method [POST]
     * @author zero
     */
    public function clean(): Json
    {
        if ($this->isAjaxPost()) {
            (new ClearValidate())->goCheck();
            ClearService::clean($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}