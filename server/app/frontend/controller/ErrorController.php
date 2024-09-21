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

namespace app\frontend\controller;

use app\common\basics\Frontend;
use app\common\enums\ErrorEnum;
use app\common\exception\SystemException;
use think\response\View;

/**
 * 错误管理
 */
class ErrorController extends Frontend
{
    protected array $notNeedLogin = ['wrong'];

    /**
     * 控制器不存在
     *
     * @param $name
     * @param $arguments
     * @throws SystemException
     * @author zero
     */
    public function __call($name, $arguments): void
    {
        $errCode = ErrorEnum::CONTROl_ERROR;
        $message = ErrorEnum::getMsgByCode($errCode);
        throw new SystemException($message, $errCode);
    }

    /**
     * 异常的页面
     *
     * @return View
     * @author zero
     */
    public function wrong(): View
    {
        $error = session('error');
        $error = json_decode($error ? : '{}', true);

        session('error', null);
        return view('common/error', [
            'errCode' => $error['errCode'] ?? '',
            'errMsg'  => $error['errMsg'] ?? ''
        ]);
    }
}