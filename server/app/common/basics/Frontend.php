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
use app\common\model\DevNavigation;
use app\common\utils\AjaxUtils;
use app\common\utils\ArrayUtils;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\View;

/**
 * 前端基类
 *
 * Class Frontend
 * @package app\common\basics
 */
abstract class Frontend extends BaseController
{
    protected int $terminal = 1;

    /**
     * 用户ID
     */
    protected int $userId = 1;

    /**
     * 用户信息
     */
    protected array $userInfo;

    /**
     * 不校验登录的方法
     */
    protected array $notNeedLogin = [];

    /**
     * 构造方法
     *
     * Frontend constructor.
     * @param App $app
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function __construct(App $app)
    {
        parent::__construct($app);

        if (!$this->isLogin()) {
            if ($this->request->isAjax()) {
                AjaxUtils::error('尚未登录,请登录后再操作!');
            }

            $this->redirect('/frontend/login/login', 302);
        }

        $this->setValues();

        $this->initialize();
    }

    /**
     * 初始方法
     */
    protected function initialize(): void
    {}

    /**
     * 设置变量
     *
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    protected function setValues(): void
    {
        $modelNavigation = new DevNavigation();
        $navigationData = $modelNavigation
            ->field('id,pid,name,target,url')
            ->where(['is_disable' => 0])
            ->where(['is_delete' => 0])
            ->order('sort desc, id desc')
            ->select()->toArray();

        $pcConfig = ConfigUtils::get('pc');
        $pcConfig['logo'] = UrlUtils::toAbsoluteUrl($pcConfig['logo']??'');

        View::assign('pc', $pcConfig);
        View::assign('website', ConfigUtils::get('website'));
        View::assign('navigation', ArrayUtils::toTreeJson($navigationData));
    }

    /**
     * 验证登录
     *
     * @author windy
     * @return bool
     */
    protected function isLogin(): bool
    {
        $userInfo = session('userInfo');
        if (in_array(request()->action(), $this->notNeedLogin)) {
            if ($userInfo) {
                $this->userInfo = $userInfo;
                $this->userId = intval($userInfo['id']);
            }
            return true;
        } else {
            if ($userInfo) {
                $this->userInfo = $userInfo;
                $this->userId = intval($userInfo['id']);
                return true;
            }
            return false;
        }
    }
}