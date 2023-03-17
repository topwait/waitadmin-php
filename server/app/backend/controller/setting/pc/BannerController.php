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


use app\backend\service\setting\pc\BannerService;
use app\backend\validate\PageValidate;
use app\backend\validate\setting\BannerValidate;
use app\common\basics\Backend;
use app\common\model\DevBanner;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 轮播图配置管理
 */
class BannerController extends Backend
{
    /**
     * 轮播图列表
     *
     * @return Json|View
     * @throws DbException
     * @author windy
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            (new PageValidate())->goCheck();
            $list = BannerService::lists($this->request->get());
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 轮播图新增
     *
     * @return Json|View
     * @author windy
     */
    public function add(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new BannerValidate())->addCheck();
            BannerService::add($this->request->post());
            return AjaxUtils::success();
        }

        return view('', [
            'position' => DevBanner::positionEnum()
        ]);
    }

    /**
     * 轮播图编辑
     *
     * @return Json|View
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author windy
     */
    public function edit(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new BannerValidate())->editCheck();
            BannerService::edit($this->request->post());
            return AjaxUtils::success();
        }

        $id = intval($this->request->get('id'));
        return view('', [
            'detail'   => BannerService::detail($id),
            'position' => DevBanner::positionEnum()
        ]);
    }

    /**
     * 轮播图删除
     *
     * @return Json|View
     * @author windy
     */
    public function del(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new BannerValidate())->idsCheck();
            BannerService::del($this->request->post('ids'));
            return AjaxUtils::success();
        }

        return view();
    }
}