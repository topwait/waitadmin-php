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

use app\common\utils\FileUtils;
use think\console\Command;
use think\console\Input;
use think\console\Output;

/**
 * 垃圾清理器
 */
class Gc extends Command
{
    /**
     * 指令配置
     */
    protected function configure(): void
    {
        $this->setName('gc')
            ->setDescription('垃圾清理器');
    }

    /**
     * 执行任务
     *
     * @param Input $input
     * @param Output $output
     * @return int|null
     * @author zero
     */
    protected function execute(Input $input, Output $output): ?int
    {
        $yesterday = intval(date('Ymd', time()-86400));
        $dirsArray = [];
        $pathArray = [
            public_path() . 'temporary/'
        ];

        // 获取待处理的目录文件
        foreach ($pathArray as $path) {
            if (file_exists($path) && $handle = opendir($path)) {
                while (($file = readdir($handle)) !== false) {
                    if ($file != '.' && $file != '..') {
                        $filePath = $path . $file;
                        if (is_dir($filePath) && (intval($file) < $yesterday)) {
                            $dirsArray[] = $filePath;
                        } elseif (is_file($filePath)) {
                            $dirsArray[] = $filePath;
                        }
                    }
                }
            }
        }

        // 检测文件并进行处理
        foreach ($dirsArray as $path) {
            if (file_exists($path)) {
                FileUtils::rmdir($path);
            }
        }

        return 1;
    }
}