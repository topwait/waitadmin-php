<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------

return [
    // 指令定义
    'commands' => [
        // 任务调度器
        'crontab' => 'app\common\crontab\Crontab',
        // 垃圾清理器
        'gc'      => 'app\common\crontab\Gc',


        // 插件的指令
        'addon'   => 'app\common\command\Addon',
        // 工具箱指令
        'wa'      => 'app\common\command\Wa',
    ],
];
