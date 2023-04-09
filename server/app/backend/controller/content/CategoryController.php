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

namespace app\backend\controller\content;

use app\backend\service\content\CategoryService;
use app\backend\validate\content\CategoryValidate;
use app\backend\validate\PageValidate;
use app\common\basics\Backend;
use app\common\exception\OperateException;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 文章分类管理
 */
class CategoryController extends Backend
{
    /**
     * 文章分类列表
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
            $list = CategoryService::lists();
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 文章分类新增
     *
     * @return Json|View
     * @method [GET|POST]
     * @author zero
     */
    public function add(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new CategoryValidate())->addCheck();
            CategoryService::add($this->request->post());
            return AjaxUtils::success();
        }

        return view();
    }

    /**
     * 文章分类编辑
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
            CategoryService::edit($this->request->post());
            return AjaxUtils::success();
        }

        (new CategoryValidate())->idCheck();
        $id = intval($this->request->get('id'));

        return view('', [
            'detail' => CategoryService::detail($id)
        ]);
    }

    /**
     * 文章分类删除
     *
     * @return Json
     * @throws OperateException
     * @method [POST]
     * @author zero
     */
    public function del(): Json
    {
        if ($this->isAjaxPost()) {
            (new CategoryValidate())->idCheck();
            CategoryService::del(intval($this->request->post('id')));
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}