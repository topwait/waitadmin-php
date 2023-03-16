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
use app\common\utils\ArrayUtils;
use app\common\utils\ConfigUtils;
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

        View::assign('seo', ConfigUtils::get('seo'));
        View::assign('website', ConfigUtils::get('pc'));
        View::assign('navigation', ArrayUtils::toTreeJson($navigationData));
    }
}