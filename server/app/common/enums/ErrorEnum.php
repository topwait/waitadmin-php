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

namespace app\common\enums;

/**
 * 错误枚举类
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
            self::SYSTEM_ERROR     => '系统错误异常',
            self::PARAMS_ERROR     => '请求参数错误',
            self::METHOD_ERROR     => '方法名不存在',
            self::CONTROl_ERROR    => '控制器不存在',
            self::REQUEST_ERROR    => '请求异常',
            self::OPERATE_ERROR    => '操作错误',
            self::UPLOADS_ERROR    => '上传错误',
            self::PURVIEW_ERROR    => '权限不足',
            self::FOUNDER_ERROR    => '失败查询',

            self::LOGIN_EMPTY_ERROR   => '登录令牌缺失',
            self::LOGIN_EXPIRE_ERROR  => '登录令牌失效'
        ];

        return $desc[$code] ?? '未知异常';
    }
}