<?php
// +----------------------------------------------------------------------
// | Cookie设置
// +----------------------------------------------------------------------

return [
    // cookie 保存时间
    'expire'    => 0,
    // cookie 保存路径
    'path'      => '/',
    // cookie 有效域名
    'domain'    => '',
    // cookie 安全传输
    'secure'    => false,
    // httponly  默认false
    'httponly'  => false,
    // setcookie 默认true
    'setcookie' => true,
    // samesite  支持['strict', 'lax']
    'samesite'  => '',
];
