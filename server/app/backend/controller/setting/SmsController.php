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

use app\backend\service\setting\SmsService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\response\Json;
use think\response\View;

/**
 * 短信配置管理
 */
class SmsController extends Backend
{
    /**
     * 短信引擎列表
     *
     * @return View|Json
     * @method [GET]
     * @author windy
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            $list = SmsService::lists();
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 短信引擎配置
     *
     * @return View|Json
     * @method [POST]
     * @author windy
     */
    public function save(): View|Json
    {
        if ($this->isAjaxPost()) {
            SmsService::save($this->request->post());
            return AjaxUtils::success();
        }

        $alias = $this->request->get('alias');
        return view('setting/sms/'.$alias, [
            'detail' => SmsService::detail($alias),
        ]);
    }
}