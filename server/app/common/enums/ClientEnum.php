<?php

namespace app\common\enums;

/**
 * 客户端枚举类
 */
class ClientEnum
{
    const MNP     = 1;  // 微信小程序
    const OA      = 2;  // 微信公众号
    const H5      = 3;  // H5(非微信)
    const PC      = 4;  // 电脑端
    const ANDROID = 5;  // 安卓端
    const IOS     = 6;  // 苹果端

    /**
     * 获取枚举对应文本
     *
     * @param int $code
     * @return string
     * @author zero
     */
    public static function getMsgByCode(int $code): string
    {
        $desc = [
            self::MNP     => '微信小程序',
            self::OA      => '公众号',
            self::H5      => 'H5',
            self::PC      => '电脑',
            self::ANDROID => '安卓',
            self::IOS     => '苹果'
        ];

        return $desc[$code] ?? '未知异常';
    }
}