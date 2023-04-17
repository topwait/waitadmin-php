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

namespace app\backend\controller\setting\pc;

use app\backend\service\setting\pc\NavigationService;
use app\backend\validate\setting\NavigationValidate;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use app\common\utils\ArrayUtils;
use think\db\exception\DbException;
use think\response\Json;
use think\response\View;

/**
 * 导航配置管理
 */
class NavigationController extends Backend
{
    /**
     * 导航列表
     *
     * @return Json|View
     * @throws DbException
     * @method [GET]
     * @author zero
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            $list = NavigationService::lists();
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 导航新增
     *
     * @return Json|View
     * @throws DbException
     * @method [GET|POST]
     * @author zero
     */
    public function add(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new NavigationValidate())->addCheck();
            NavigationService::add($this->request->post());
            return AjaxUtils::success();
        }

        return view('', [
            'navigation' => ArrayUtils::toTreeHtml(NavigationService::lists())
        ]);
    }

    /**
     * 导航编辑
     *
     * @return Json|View
     * @throws DbException
     * @method [GET|POST]
     * @author zero
     */
    public function edit(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new NavigationValidate())->editCheck();
            NavigationService::edit($this->request->post());
            return AjaxUtils::success();
        }

        $id = intval($this->request->get('id'));
        return view('', [
            'detail'     => NavigationService::detail($id),
            'navigation' => ArrayUtils::toTreeHtml(NavigationService::lists())
        ]);
    }

    /**
     * 导航删除
     *
     * @return Json
     * @method [POST]
     * @author zero
     */
    public function del(): Json
    {
        if ($this->isAjaxPost()) {
            (new NavigationValidate())->idCheck();
            NavigationService::del($this->request->post('id'));
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}