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
use app\common\enum\ErrorEnum;
use app\common\exception\OperateException;
use app\common\service\security\SecurityDriver;
use think\App;

/**
 * 接口基类
 *
 * Class Api
 * @package app\common\basics
 */
class Api extends BaseController
{
    /**
     * 客户端
     * @var int
     */
    protected int $terminal;

    /**
     * 用户ID
     * @var int
     */
    protected int $userId;

    /**
     * 不校验登录的方法
     * @var array
     */
    protected array $notNeedLogin = [];

    /**
     * 构造方法
     *
     * Backend constructor.
     * @param App $app
     * @throws OperateException
     */
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->checkLogin();

        $this->initialize();
    }

    /**
     * 初始方法
     *
     * @return void
     * @author windy
     */
    protected function initialize(): void
    {}

    /**
     * 登录验证
     *
     * @return bool
     * @throws OperateException
     * @author windy
     */
    protected function checkLogin(): bool
    {
        $token    = strval(request()->header('token') ?? '');
        $terminal = intval(request()->header('terminal') ?? 0);
        $userId   = SecurityDriver::checkLogin($token);

        // 判断是否是免登录的方法
        if (in_array(request()->action(), $this->notNeedLogin)) {
            $this->userId   = $userId;
            $this->terminal = $terminal;
            return true;
        }

        // 验证是否是尚未登陆状态
        if (empty($token)) {
            $errCode = ErrorEnum::LOGIN_EMPTY_ERROR;
            $errMsg = ErrorEnum::getMsgByCode($errCode);
            throw new OperateException($errMsg, $errCode);
        }

        // 验证是否是尚未登陆状态
        if (!$userId) {
            $errCode = ErrorEnum::LOGIN_EXPIRE_ERROR;
            $errMsg = ErrorEnum::getMsgByCode($errCode);
            throw new OperateException($errMsg, $errCode);
        }

        // 保存信息到全局的属性
        $this->userId   = $userId;
        $this->terminal = $terminal;
        return true;
    }
}