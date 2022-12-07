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


namespace app\common\exception;


use Exception;

/**
 * 自定义异常基类
 *
 * Class BaseException
 * @package app\common\exception
 */
abstract class BaseException extends Exception
{
    /**
     * 错误描述
     * @var string
     */
    public $message = "";

    /**
     * 错误编码
     * @var int
     */
    public $code    = 0;

    /**
     * 响应数据
     * @var array
     */
    public array $data    = [];
}