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

namespace app\backend\controller;

use app\backend\service\IndexService;
use app\common\basics\Backend;
use app\common\utils\ArrayUtils;
use think\response\View;

/**
 * 主页管理
 */
class IndexController extends Backend
{
    protected array $notNeedPower = ['setting'];

    /**
     * 主页
     *
     * @return View
     * @method [GET]
     * @author zero
     */
    public function index(): View
    {
        $detail = IndexService::index($this->adminId, intval($this->adminUser['role_id']));
        return view('index', [
            'menus'     => ArrayUtils::toTreeJson($detail['menus']),
            'config'    => $detail['config'],
            'adminUser' => $detail['adminUser']
        ]);
    }

    /**
     * 控制台
     *
     * @return View
     * @method [GET]
     * @author zero
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
     * @method [GET]
     * @author zero
     */
    public function setting(): View
    {
        return view();
    }
}