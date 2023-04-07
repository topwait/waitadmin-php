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

use app\backend\service\setting\NoticeService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 通知设置管理
 */
class NoticeController extends Backend
{
    /**
     * 场景通知列表
     *
     * @return Json|View
     * @throws DbException
     * @method [GET]
     * @author zero
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            $list = NoticeService::lists($this->request->get());
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 场景通知详情
     *
     * @return View|Json
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @method [GET|POST]
     * @author zero
     */
    public function edit(): View|Json
    {
        if ($this->isAjaxPost()) {
            NoticeService::edit($this->request->post());
            return AjaxUtils::success();
        }

        $id = intval($this->request->get('id'));
        return view('', [
            'detail' => NoticeService::detail($id)
        ]);
    }
}