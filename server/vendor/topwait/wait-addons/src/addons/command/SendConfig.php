<?php
// +----------------------------------------------------------------------
// | 基于ThinkPHP6的插件化模块 [WaitAdmin专属订造]
// +----------------------------------------------------------------------
// | github: https://github.com/topwait/wait-addons
// | Author: zero <2474369941@qq.com>
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace wait\addons\command;

use InvalidArgumentException;
use RuntimeException;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class SendConfig extends Command
{
    public function configure()
    {
        $this->setName('addons:config')
            ->setDescription('send config to config folder');
    }

    public function execute(Input $input, Output $output): void
    {
        // 获取默认配置文件
        $content = file_get_contents(root_path() . 'vendor/topwait/wait-addons/src/config.php');
        $configPath = config_path() . '/';
        $configFile = $configPath . 'addons.php';

        // 判断目录是否存在
        if (!file_exists($configPath)) {
            mkdir($configPath, 0755, true);
        }

        // 判断文件是否存在
        if (is_file($configFile)) {
            throw new InvalidArgumentException(sprintf('The config file "%s" already exists', $configFile));
        }

        // 判断文件是否可写
        if (false === file_put_contents($configFile, $content)) {
            throw new RuntimeException(sprintf('The config file "%s" could not be written to "%s"', $configFile,$configPath));
        }

        $output->writeln('create addons config ok');
    }
}