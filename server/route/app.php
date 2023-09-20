<?php
// +----------------------------------------------------------------------
// | 路由设置
// +----------------------------------------------------------------------

use think\facade\Route;

Route::get('waitAdmin', function () {
    // TP的多应用模式,路由写在这里是不生效的
    // 您需要自定义路由需要在对应的应用目录下创建一个route文件夹
    // 如: frontend/route/route.php 在这里正常写路由才生效
    return 'hello,WaitAdmin!';
});

// 手机路由
Route::rule('mobile/:any', function () {
    return view(app()->getRootPath().'public/mobile/index.html');
})->pattern(['any' => '\w+']);

