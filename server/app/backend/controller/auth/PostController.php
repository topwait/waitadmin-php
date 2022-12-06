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

namespace app\backend\controller\auth;


use app\backend\service\auth\PostService;
use app\backend\validate\auth\PostValidate;
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
 * 岗位管理
 *
 * Class PostController
 * @package app\admin\controller\auth
 */
class PostController extends Backend
{
    /**
     * 岗位列表
     *
     * @return Json|View
     * @throws DbException
     * @author windy
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            (new PageValidate())->goCheck();
            $list = PostService::lists($this->request->get());
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 岗位新增
     *
     * @return Json|View
     * @author windy
     */
    public function add(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new PostValidate())->addCheck();
            PostService::add($this->request->post());
            return AjaxUtils::success();
        }

        return view();
    }

    /**
     * 岗位编辑
     *
     * @return Json|View
     * @throws OperateException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author windy
     */
    public function edit(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new PostValidate())->editCheck();
            PostService::edit($this->request->post());
            return AjaxUtils::success();
        }

        (new PostValidate())->idCheck();
        $id = intval($this->request->get('id'));

        return view('', [
            'detail' => PostService::detail($id)
        ]);
    }

    /**
     * 岗位删除
     *
     * @return Json
     * @throws OperateException
     * @author windy
     */
    public function del(): Json
    {
        if ($this->isAjaxPost()) {
            (new PostValidate())->idCheck();
            PostService::del($this->request->post('id'));
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}