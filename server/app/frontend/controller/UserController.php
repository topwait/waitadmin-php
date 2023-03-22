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

namespace app\frontend\controller;

use app\common\basics\Frontend;
use think\response\View;

/**
 * 用户管理
 */
class UserController extends Frontend
{
    /**
     * 个人中心
     *
     * @return View
     * @author windy
     */
    public function index(): View
    {
        return view();
    }

    public function update(): View
    {
        return view();
    }

    public function account(): View
    {
        return view();
    }

    public function collect(): View
    {
        return view();
    }
}