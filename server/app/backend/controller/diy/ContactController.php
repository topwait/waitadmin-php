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

namespace app\backend\controller\diy;

use app\backend\service\diy\ContactService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

/**
 * 客服装修管理
 */
class ContactController extends Backend
{
    /**
     * 客服装修详情
     *
     * @return View
     * @method [GET]
     * @author zero
     */
    public function index(): View
    {
        $detail = ContactService::detail();
        return view('', [
            'detail' => $detail,
            'jsonp' => json_encode($detail),
        ]);
    }

    /**
     * 客服装修保存
     *
     * @return Json
     * @method [POST]
     * @author zero
     */
    public function save(): Json
    {
        if ($this->isAjaxPost()) {
            ContactService::save($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}