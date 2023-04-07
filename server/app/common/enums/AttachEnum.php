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
 * 附件枚举类
 */
class AttachEnum
{
    const PICTURE  = 10;
    const VIDEO    = 20;
    const DOCUMENT = 30;
    const PACKAGE  = 40;

    /**
     * 根据描述获取Code
     *
     * @author zero
     * @param string $msg
     * @return int
     */
    public static function getCodeByMsg(string $msg): int
    {
        $desc = [
            'picture'  => self::PICTURE,
            'video'    => self::VIDEO,
            'document' => self::DOCUMENT,
            'package'  => self::PACKAGE,
        ];

        return $desc[$msg] ?? 0;
    }
}