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


namespace app\common\enum;


/**
 * 性别枚举类
 *
 * Class SexEnum
 * @package app\common\enum
 */
class GenderEnum
{
    const MALE = 1; //男性
    const GIRL = 2; //女性

    /**
     * 根据Code获取描述
     *
     * @param int $code
     * @return string
     * @author windy
     */
    public static function getMsgByCode(int $code): string
    {
        $desc = [
            self::MALE => '男',
            self::GIRL => '女'
        ];

        return $desc[$code] ?? '未知';
    }
}