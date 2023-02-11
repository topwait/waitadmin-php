<?php
// +----------------------------------------------------------------------
// | 全局中间件定义文件
// +----------------------------------------------------------------------

use app\common\http\middleware\AllowCrossDomain;
use app\common\http\middleware\LogsMiddleware;
use think\middleware\LoadLangPack;
use think\middleware\SessionInit;

return [
    // 多语言加载
    LoadLangPack::class,
    // Session初始化
    SessionInit::class,
    // 系统跨域中间件
    AllowCrossDomain::class,
    // 系统日志中间件
    LogsMiddleware::class
];
