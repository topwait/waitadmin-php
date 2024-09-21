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

namespace app\common\crontab;

use app\common\model\sys\SysCrontab;
use app\common\utils\TimeUtils;
use Cron\CronExpression;
use Exception;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Console;

/**
 * 计划任务调度器
 */
class Crontab extends Command
{
    /**
     * 指令配置
     */
    protected function configure(): void
    {
        $this->setName('crontab')
            ->setDescription('定时任务');
    }

    /**
     * 执行任务
     *
     * @param Input $input
     * @param Output $output
     * @return bool|int|null
     * @throws @\think\db\exception\DataNotFoundException
     * @throws @\think\db\exception\DbException
     * @throws @\think\db\exception\ModelNotFoundException
     */
    protected function execute(Input $input, Output $output): bool|int|null
    {
        $crontab = (new SysCrontab())->where(['status'=>1])->select()->toArray();

        if (empty($crontab)) {
            return false;
        }

        $startTime = time();
        foreach ($crontab as $cron) {
            if (CronExpression::isValidExpression($cron['rules']) === false) {
                continue;
            }

            $nextTime = (new CronExpression($cron['rules']))
                ->getNextRunDate($cron['last_time'])
                ->getTimestamp();

            if ($nextTime >= time()) {
                continue; // 未到执行时间
            }

            try {
                $startTime = TimeUtils::millisecond();
                $parameter = explode(' ', $cron['params']);
                if (is_array($parameter) && !empty($cron['params'])) {
                    Console::call($cron['command'], $parameter);
                } else {
                    Console::call($cron['command']);
                }
                SysCrontab::update(['error'=>''], ['id'=>$cron['id']]);
            } catch (Exception $e) {
                SysCrontab::update(['error'=>$e->getMessage(), 'status'=>3], ['id'=>$cron['id']]);
            } finally {
                $endTime = TimeUtils::millisecond() - $startTime;
                $maxTime = max($cron['max_time'], $endTime);
                SysCrontab::update(['last_time'=>time(), 'exe_time'=>$endTime, 'max_time'=>$maxTime], ['id'=>$cron['id']]);
            }
        }

        return true;
    }
}