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

use app\backend\service\setting\pc\LinksService;
use app\backend\validate\PageValidate;
use app\backend\validate\setting\LinksValidate;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 友情链接配置管理
 */
class LinksController extends Backend
{
    /**
     * 友情链接列表
     *
     * @return Json|View
     * @throws DbException
     * @method [GET]
     * @author windy
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            (new PageValidate())->goCheck();
            $list = LinksService::lists($this->request->get());
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 友情链接新增
     *
     * @return Json|View
     * @method [GET|POST]
     * @author windy
     */
    public function add(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new LinksValidate())->addCheck();
            LinksService::add($this->request->post());
            return AjaxUtils::success();
        }

        return view();
    }

    /**
     * 友情链接编辑
     *
     * @return Json|View
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @method [GET|POST]
     * @author windy
     */
    public function edit(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new LinksValidate())->editCheck();
            LinksService::edit($this->request->post());
            return AjaxUtils::success();
        }

        $id = intval($this->request->get('id'));
        return view('', [
            'detail' => LinksService::detail($id)
        ]);
    }

    /**
     * 友情链接删除
     *
     * @return Json|View
     * @method [POST]
     * @author windy
     */
    public function del(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new LinksValidate())->idsCheck();
            LinksService::del($this->request->post('ids'));
            return AjaxUtils::success();
        }

        return view();
    }
}