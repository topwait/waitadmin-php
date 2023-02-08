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

namespace app\backend\controller\user;


use app\backend\service\user\GroupService;
use app\backend\service\user\UsersService;
use app\common\basics\Backend;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 用户管理
 *
 * Class UsersController
 * @package app\backend\controller\user
 */
class UsersController extends Backend
{
    /**
     * 用户列表
     *
     * @return Json|View
     * @throws DbException
     * @author windy
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            $list = UsersService::lists($this->request->get());
            return AjaxUtils::success($list);
        }

        return view('', [
            'groups' => GroupService::all()
        ]);
    }

    /**
     * 用户详情
     *
     * @return View
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author windy
     */
    public function detail(): View
    {
        $id = intval($this->request->get('id'));
        return view('', [
            'detail' => UsersService::detail($id)
        ]);
    }

    /**
     * 用户分组
     *
     * @return Json|View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    public function group(): View|Json
    {
        if ($this->isAjaxPost()) {
            $ids = $this->request->post('ids');
            $gid = $this->request->post('gid');
            UsersService::setGroup($ids, intval($gid));
            return AjaxUtils::success();
        }

        return view('', [
            'groups' => GroupService::all()
        ]);
    }

    /**
     * 在线的用户
     *
     * @return View|Json
     * @author windy
     */
    public function line(): View|Json
    {
        if ($this->isAjaxGet()) {
            $id = intval($this->request->get('id', 0));
            $list = UsersService::line($id);
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 踢下线用户
     *
     * @return Json
     * @author windy
     */
    public function kickOut(): Json
    {
        if ($this->isAjaxGet()) {
            $token= $this->request->get('token', '');
            UsersService::kickOut($token);
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}