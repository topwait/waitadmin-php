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


use app\common\enum\ErrorEnum;
use app\common\exception\SystemException;
use think\response\View;

/**
 * 错误管理
 *
 * Class ErrorController
 * @package app\admin\controller
 */
class ErrorController
{
    /**
     * 控制器不存在
     *
     * @param $name
     * @param $arguments
     * @throws SystemException
     * @author windy
     */
    public function __call($name, $arguments)
    {
        $errCode = ErrorEnum::CONTROl_ERROR;
        $message = ErrorEnum::getMsgByCode($errCode);
        throw new SystemException($message, $errCode);
    }

    /**
     * 异常的页面
     *
     * @return View
     * @author windy
     */
    public function wrong(): View
    {
        return view('common/error', [
            'errCode' => request()->get('errCode'),
            'errMsg'  => request()->get('errMsg')
        ]);
    }
}