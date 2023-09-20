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

use app\backend\service\content\ArticleService;
use app\backend\service\content\CategoryService;
use app\backend\validate\content\ArticleValidate;
use app\backend\validate\PageValidate;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 文章管理
 */
class ArticleController extends Backend
{
    /**
     * 文章列表
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
            $list = ArticleService::lists($this->request->get());
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 文章新增
     *
     * @return Json|View
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @method [GET|POST]
     * @author zero
     */
    public function add(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new ArticleValidate())->addCheck();
            ArticleService::add($this->request->post());
            return AjaxUtils::success();
        }

        return view('', [
            'category' => CategoryService::all()
        ]);
    }

    /**
     * 文章编辑
     *
     * @return Json|View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @method [GET|POST]
     * @author zero
     */
    public function edit(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new ArticleValidate())->editCheck();
            ArticleService::edit($this->request->post());
            return AjaxUtils::success();
        }

        (new ArticleValidate())->idCheck();
        $id = intval($this->request->get('id'));

        return view('', [
            'category' => CategoryService::all(),
            'detail'   => ArticleService::detail($id)
        ]);
    }

    /**
     * 文章删除
     *
     * @return Json
     * @method [POST]
     * @author zero
     */
    public function del(): Json
    {
        if ($this->isAjaxPost()) {
            (new ArticleValidate())->idsCheck();
            ArticleService::del($this->request->post('ids'));
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}