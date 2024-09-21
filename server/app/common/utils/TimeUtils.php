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

namespace app\common\utils;


use JetBrains\PhpStorm\Pure;

/**
 * 时间工具
 */
class TimeUtils
{
    /**
     * 返回今日开始和结束的时间戳
     *
     * @author zero
     * @return array
     */
    public static function today(): array
    {
        list($y, $m, $d) = explode('-', date('Y-m-d'));
        return [
            mktime(0, 0, 0, $m, $d, $y),
            mktime(23, 59, 59, $m, $d, $y)
        ];
    }

    /**
     * 返回明天开始和结束时间戳
     *
     * @author zero
     * @return array
     */
    public static function tomorrow(): array
    {
        $date = date("Y-m-d",strtotime("+1 day"));
        return [
            strtotime($date.' 00:00:00'),
            strtotime($date.' 23:59:59')
        ];
    }

    /**
     * 返回昨日开始和结束的时间戳
     *
     * @author zero
     * @return array
     */
    public static function yesterday(): array
    {
        $yesterday = date('d') - 1;
        return [
            mktime(0, 0, 0, date('m'), $yesterday, date('Y')),
            mktime(23, 59, 59, date('m'), $yesterday, date('Y'))
        ];
    }

    /**
     * 返回本周开始和结束的时间戳
     *
     * @author zero
     * @return array
     */
    public static function week(): array
    {
        list($y, $m, $d, $w) = explode('-', date('Y-m-d-w'));
        if($w == 0) $w = 7; //修正周日的问题
        return [
            mktime(0, 0, 0, $m, $d - $w + 1, $y), mktime(23, 59, 59, $m, $d - $w + 7, $y)
        ];
    }

    /**
     * 返回上周开始和结束的时间戳
     *
     * @author zero
     * @return array
     */
    public static function lastWeek(): array
    {
        $timestamp = time();
        return [
            strtotime(date('Y-m-d', strtotime("last week Monday", $timestamp))),
            strtotime(date('Y-m-d', strtotime("last week Sunday", $timestamp))) + 24 * 3600 - 1
        ];
    }

    /**
     * 返回本月开始和结束的时间戳
     *
     * @return array
     * @author zero
     */
    public static function month(): array
    {
        list($y, $m, $t) = explode('-', date('Y-m-t'));
        return [
            mktime(0, 0, 0, $m, 1, $y),
            mktime(23, 59, 59, $m, $t, $y)
        ];
    }

    /**
     * 返回上个月开始和结束的时间戳
     *
     * @author zero
     * @return array
     */
    public static function lastMonth(): array
    {
        $y = date('Y');
        $m = date('m');
        $begin = mktime(0, 0, 0, $m - 1, 1, $y);
        $end = mktime(23, 59, 59, $m - 1, date('t', $begin), $y);

        return [$begin, $end];
    }

    /**
     * 返回今年开始和结束的时间戳
     *
     * @author zero
     * @return array
     */
    public static function year(): array
    {
        $y = date('Y');
        return [
            mktime(0, 0, 0, 1, 1, $y),
            mktime(23, 59, 59, 12, 31, $y)
        ];
    }

    /**
     * 返回去年开始和结束的时间戳
     *
     * @author zero
     * @return array
     */
    public static function lastYear(): array
    {
        $year = date('Y') - 1;
        return [
            mktime(0, 0, 0, 1, 1, $year),
            mktime(23, 59, 59, 12, 31, $year)
        ];
    }

    /**
     * 获取几天前零点到现在/昨日结束的时间戳
     *
     * @author zero
     * @param int $day 天数
     * @param bool $now 返回现在或者昨天结束时间戳
     * @return array
     */
    public static function dayToNow(int $day = 1, bool $now = true): array
    {
        $end = time();
        if (!$now) {
            list($foo, $end) = self::yesterday();
        }

        unset($foo);
        return [
            mktime(0, 0, 0, date('m'), date('d') - $day, date('Y')),
            $end
        ];
    }

    /**
     * 返回几天前的时间戳
     *
     * @author zero
     * @param int $day
     * @return int
     */
    public static function daysAgo(int $day = 1): int
    {
        $nowTime = time();
        return $nowTime - self::daysToSecond($day);
    }

    /**
     * 返回几天后的时间戳
     *
     * @author zero
     * @param int $day
     * @return int
     */
    public static function daysAfter(int $day = 1): int
    {
        $nowTime = time();
        return $nowTime + self::daysToSecond($day);
    }

    /**
     * 天数转换成秒数
     *
     * @author zero
     * @param int $day
     * @return int
     */
    #[Pure]
    public static function daysToSecond(int $day = 1): int
    {
        return intval($day * 86400);
    }

    /**
     * 周数转换成秒数
     *
     * @author zero
     * @param int $week
     * @return int
     */
    #[Pure]
    public static function weekToSecond(int $week = 1): int
    {
        return intval(self::daysToSecond() * 7 * $week);
    }

    /**
     * 最近N天的日期
     *
     * @author zero
     * @param int $day
     * @return array
     */
    public static function nearToDate(int $day = 7): array
    {
        $time = time();
        $date = [];
        for ($i=1; $i<=$day; $i++){
            $date[$i-1] = date('Y-m-d' ,strtotime( '+' . ($i - $day) .' days', $time));
        }
        return $date;
    }

    /**
     * 当前毫秒数
     *
     * @author zero
     * @return float
     */
    public static function millisecond(): float
    {
        list($mse, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($mse) + floatval($sec)) * 1000);
    }

    /**
     * 返回今天是周几
     *
     * @author zero
     * @return mixed
     */
    public static function dayWeek(): string
    {
        $week = ['周日', '周一', '周二', '周三', '周四', '周五', '周六'];
        return $week[date('w')];
    }

    /**
     * 大写月份
     *
     * @author zero
     * @param int $month
     * @return string
     */
    public static function capMonth(int $month=0): string
    {
        $month = $month > 0 ? $month : intval(date('m'));
        return match ($month) {
            1 => '一月',
            2 => '二月',
            3 => '三月',
            4 => '四月',
            5 => '五月',
            6 => '六月',
            7 => '七月',
            8 => '八月',
            9 => '九月',
            10 => '十月',
            11 => '十一月',
            12 => '十二月',
            default => '未知月份',
        };
    }

    /**
     * 格式化时间戳
     *
     * @param int $time
     * @return string
     */
    #[Pure]
    public static function formatTime(int $time): string
    {
        if ($time > 86400) {
            $days = intval($time / 86400);
            $hour = intval(($time - ($days * 86400)) / 3600);
            return $days.'天'.$hour.'时';
        } else {
            $hour   = intval($time / 3600);
            $minute = intval(($time - ($hour * 3600)) / 60);
            return $hour.'时'.$minute.'分';
        }
    }

}