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

namespace app\backend\controller;


use app\backend\service\IndexService;
use app\common\basics\Backend;
use app\common\utils\ArrayUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\View;

/**
 * 主页管理
 *
 * Class IndexController
 * @package app\admin\controller
 */
class IndexController extends Backend
{
    /**
     * 主页
     *
     * @return View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    public function index(): View
    {
        $detail = IndexService::index($this->adminId);
        return view('index', [
            'menus'     => ArrayUtils::toTreeJson($detail['menus']),
            'adminUser' => $detail['adminUser']
        ]);
    }

    /**
     * 控制台
     *
     * @return View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    public function console(): View
    {
        return view('', [
            'detail' => IndexService::console()
        ]);
    }

    /**
     * 设置弹窗
     *
     * @return View
     * @author windy
     */
    public function setting(): View
    {
        return view();
    }
}