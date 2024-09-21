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

namespace app\common\basics;

use app\BaseController;
use app\common\cache\PermsCache;
use app\common\enums\ErrorEnum;
use app\common\exception\NotAuthException;
use app\common\model\auth\AuthMenu;
use app\common\model\auth\AuthPerm;
use think\App;

/**
 * 后台基类
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
    protected int $adminId=0;

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
     * @author zero
     * @return void
     */
    protected function initialize(): void
    {}

    /**
     * 验证登录
     *
     * @return bool
     * @throws NotAuthException
     * @author zero
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
     * @author zero
     */
    protected function checkPower(): bool
    {
        $prefixName = config('project.backend_entrance');

        $requestUrl = lcfirst(str_replace($prefixName, '', request()->baseUrl()));
        $requestUrl = ltrim($requestUrl, '/');
        $requestUrl = ($requestUrl == 'index' || !$requestUrl) ? 'index/index' : $requestUrl;

        if (in_array(request()->action(), $this->notNeedLogin) ||
            in_array(request()->action(), $this->notNeedPower) ||
            $requestUrl === 'index/index' ||
            $this->adminId === 1)
        {
            if ($this->adminId) {
                PermsCache::set($this->adminId, ['*']);
            }
            return true;
        }

        $authPerm = new AuthPerm();
        $authMenu = new AuthMenu();

        $menus = $authPerm->field(true)
            ->where(['role_id'=>intval($this->adminUser['role_id'])])
            ->column('menu_id');

        $perms = array_unique($authMenu->field(true)
            ->whereIn('id', $menus)
            ->where(['is_delete'=>0])
            ->where(['is_disable'=>0])
            ->order('sort asc, id asc')
            ->column('perms'));

        PermsCache::set($this->adminId, $perms);

        $perms = array_map(function ($p) {
            return strtolower($p);
        }, $perms);

        if (!in_array(strtolower($requestUrl), $perms)) {
            if (request()->isAjax()) {
                throw new NotAuthException();
            }

            session('error', json_encode([
                'errCode' => ErrorEnum::PURVIEW_ERROR,
                'errMsg'  => ErrorEnum::getMsgByCode(ErrorEnum::PURVIEW_ERROR),
            ]));

            $this->redirect((string) url('error/wrong'), 302);
        }

        return false;
    }
}