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
use app\common\enums\ClientEnum;
use app\common\model\dev\DevNavigation;
use app\common\utils\ArrayUtils;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;
use app\frontend\service\UserService;
use LogicException;
use think\App;
use think\facade\Cookie;
use think\facade\View;

/**
 * 前端基类
 */
abstract class Frontend extends BaseController
{
    /**
     * 设备
     * @var int
     */
    protected int $terminal = ClientEnum::PC;

    /**
     * 用户ID
     * @var int
     */
    protected int $userId = 0;

    /**
     * 用户信息
     * @var array
     */
    protected array $userInfo = [];

    /**
     * 不校验登录的方法
     */
    protected array $notNeedLogin = [];

    /**
     * 构造方法
     *
     * Frontend constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        parent::__construct($app);

        if (!$this->isLogin()) {
            if ($this->request->isAjax()) {
                throw new LogicException('请登录后再操作!');
            }

            Cookie::set('logon','1', ['expire'=>60,'path'=>'/']);
            $this->redirect(route('index/index'), 302);
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
     * @author zero
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
        View::assign('userInfo', $this->userInfo);
        View::assign('action', $this->request->action());
        View::assign('website', ConfigUtils::get('website'));
        View::assign('navigation', ArrayUtils::toTreeJson($navigationData));
    }

    /**
     * 验证登录
     *
     * @author zero
     * @return bool
     */
    protected function isLogin(): bool
    {
        $userId = session('userId');
        if (in_array(request()->action(), $this->notNeedLogin)) {
            if ($userId) {
                $this->userId = intval($userId);
                $this->userInfo = UserService::info($this->userId);
            }
            return true;
        } else {
            if ($userId) {
                $this->userId = intval($userId);
                $this->userInfo = UserService::info($this->userId);
                return true;
            }
            return false;
        }
    }
}