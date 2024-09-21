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

use app\backend\service\system\CrontabService;
use app\backend\validate\PageValidate;
use app\backend\validate\system\CrontabValidate;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 系统计划任务管理
 */
class CrontabController extends Backend
{
    /**
     * 计划任务列表
     *
     * @return Json|View
     * @throws DbException
     * @method [GET]
     * @author zero
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            (new PageValidate())->goCheck();
            $list = CrontabService::lists($this->request->get());
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 计划任务新增
     *
     * @return Json|View
     * @method [GET|POST]
     * @author zero
     */
    public function add(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new CrontabValidate())->addCheck();
            CrontabService::add($this->request->post());
            return AjaxUtils::success();
        }

        return view();
    }

    /**
     * 计划任务编辑
     *
     * @return Json|View
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @method [GET|POST]
     * @author zero
     */
    public function edit(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new CrontabValidate())->editCheck();
            CrontabService::edit($this->request->post());
            return AjaxUtils::success();
        }

        (new CrontabValidate())->idCheck();
        $id = intval($this->request->get('id'));

        return view('', [
            'detail' => CrontabService::detail($id)
        ]);
    }

    /**
     * 计划任务删除
     *
     * @return Json
     * @method [POST]
     * @author zero
     */
    public function del(): Json
    {
        if ($this->isAjaxPost()) {
            (new CrontabValidate())->idCheck();
            CrontabService::del(intval($this->request->post('id')));
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }

    /**
     * 计划任务停止
     *
     * @return Json
     * @method [POST]
     * @author zero
     */
    public function stop(): Json
    {
        if ($this->isAjaxPost()) {
            (new CrontabValidate())->idCheck();
            CrontabService::stop(intval($this->request->post('id')));
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }

    /**
     * 计划任务运行
     *
     * @return Json
     * @method [POST]
     * @author zero
     */
    public function run(): Json
    {
        if ($this->isAjaxPost()) {
            (new CrontabValidate())->idCheck();
            CrontabService::run(intval($this->request->post('id')));
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}