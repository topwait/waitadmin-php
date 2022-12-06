<?php


namespace think;

require __DIR__ . '/../vendor/autoload.php';

// 检测程序是否已安装
if (!file_exists(__DIR__ .'/../install.lock')) {
    header("location:/install/install.php");
    exit;
}

// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->name('backend')->run();

$response->send();

$http->end($response);