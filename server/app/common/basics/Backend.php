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

namespace app\common\basics;

use app\BaseController;
use app\common\exception\NotAuthException;
use app\common\model\auth\AuthMenu;
use app\common\model\auth\AuthPerm;
use think\App;

/**
 * 后台基类
 *
 * Class Backend
 * @package app\common\basics
 */
abstract class Backend extends BaseController
{
    /**
     * 管理员信息
     */
    protected array $adminUser;

    /**
     * 管理员ID
     */
    protected int $adminId;

    /**
     * 不校验登录的方法
     * @var array
     */
    protected array $notNeedLogin = [];

    /**
     * 不校验权限的方法
     * @var array
     */
    protected array $notNeedPower = [];

    /**
     * 构造方法
     *
     * Backend constructor.
     * @param App $app
     * @throws NotAuthException
     */
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->checkLogin();

        $this->checkPower();

        $this->initialize();
    }

    /**
     * 初始方法
     *
     * @author windy
     * @return void
     */
    protected function initialize(): void
    {}

    /**
     * 验证登录
     *
     * @return bool
     * @throws NotAuthException
     * @author windy
     */
    protected function checkLogin(): bool
    {
        $adminUser = session('adminUser');

        if (in_array(request()->action(), $this->notNeedLogin)) {
            if ($adminUser) {
                $this->adminUser = $adminUser;
                $this->adminId   = intval($adminUser['id']);
                return true;
            }
        } else {
            if ($adminUser) {
                $this->adminUser = $adminUser;
                $this->adminId   = intval($adminUser['id']);
                return true;
            } else {
                if ($this->request->isAjax()) {
                    throw new NotAuthException('请登录后再操作!');
                }
                $this->redirect(route('login/index'), 302);
            }
        }

        return false;
    }

    /**
     * 验证权限
     *
     * @return bool
     * @throws NotAuthException
     * @author windy
     */
    protected function checkPower(): bool
    {
        if (in_array(request()->action(), $this->notNeedLogin) ||
            in_array(request()->action(), $this->notNeedPower) ||
            $this->adminId === 1) {
            return true;
        }

        $authPerm = new AuthPerm();
        $authMenu = new AuthMenu();

        $menus = $authPerm->field(true)
            ->where(['role_id'=>intval($this->adminUser['role_id'])])
            ->column('menu_id');

        $perms = $authMenu->field(true)
            ->whereIn('id', $menus)
            ->where('is_menu', 0)
            ->order('sort asc, id asc')
            ->column('perms');

        $requestUrl = request()->controller().'/'.request()->action();
        if (!in_array($requestUrl, array_unique($perms))) {
            if (request()->isAjax()) {
                throw new NotAuthException('权限不足!');
            }
            $this->redirect((string) url('login/denied'), 302);
        }

        return false;
    }
}