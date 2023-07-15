<?php
// +----------------------------------------------------------------------
// | 路由设置
// +----------------------------------------------------------------------

use think\facade\Route;

Route::get('waitAdmin', function () {
    return 'hello,WaitAdmin!';
});

// 手机路由
Route::rule('mobile/:any', function () {
    return view(app()->getRootPath().'public/mobile/index.html');
})->pattern(['any' => '\w+']);

