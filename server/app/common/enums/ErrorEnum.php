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

namespace app\common\enums;


/**
 * 错误枚举类
 *
 * Class ErrorEnum
 * @package app\common\enum
 */
class ErrorEnum
{
    const SYSTEM_ERROR    = 5000; // 系统错误异常
    const PARAMS_ERROR    = 5100; // 请求参数错误
    const METHOD_ERROR    = 5200; // 方法名不存在
    const CONTROl_ERROR   = 5300; // 控制器不存在
    const REQUEST_ERROR   = 5400; // 请求异常
    const OPERATE_ERROR   = 5500; // 操作错误
    const UPLOADS_ERROR   = 5600; // 上传错误
    const PURVIEW_ERROR   = 5700; // 权限不足
    const FOUNDER_ERROR   = 5800; // 空的数据

    const LOGIN_EMPTY_ERROR = 8000;  // 登录令牌缺失
    const LOGIN_EXPIRE_ERROR = 8100; // 登录令牌失效

    /**
     * 根据Code获取描述
     *
     * @param int $code
     * @return string
     * @author zero
     */
    public static function getMsgByCode(int $code): string
    {
        $desc = [
            self::SYSTEM_ERROR        => __('System error'),
            self::PARAMS_ERROR        => __('Parameter error'),
            self::METHOD_ERROR        => __('Method does not exist error'),
            self::CONTROl_ERROR       => __('Control does not exist error'),
            self::REQUEST_ERROR       => __('Request exception'),
            self::OPERATE_ERROR       => __('Operation failed'),
            self::UPLOADS_ERROR       => __('Upload failed'),
            self::PURVIEW_ERROR       => __('Perms error'),
            self::FOUNDER_ERROR       => __('Query failed'),

            self::LOGIN_EMPTY_ERROR   => __('Login token missing'),
            self::LOGIN_EXPIRE_ERROR  => __('Login token has expired')
        ];

        return $desc[$code] ?? '未知异常';
    }
}