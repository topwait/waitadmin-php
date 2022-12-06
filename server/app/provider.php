<?php
// +----------------------------------------------------------------------
// | 容器Provider定义文件
// +----------------------------------------------------------------------

use app\ExceptionHandle;
use app\Request;

return [
    'think\Request'          => Request::class,
    'think\exception\Handle' => ExceptionHandle::class
];
