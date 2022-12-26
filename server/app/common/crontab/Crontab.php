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

namespace app\common\command;


use app\common\model\sys\SysCrontab;
use app\common\utils\TimeUtils;
use Exception;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Console;

/**
 * 计划任务调度器
 *
 * Class Crontab
 * @package app\common\command
 */
class Crontab extends Command
{
    /**
     * 指令配置
     */
    protected function configure()
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
                $maxTime = $cron['max_time'] > $endTime ? $cron['max_time'] : $endTime;
                SysCrontab::update(['last_time'=>time(), 'exe_time'=>$endTime, 'max_time'=>$maxTime], ['id'=>$cron['id']]);
            }
        }

        return true;
    }
}