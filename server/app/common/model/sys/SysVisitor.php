<?php

namespace app\common\model\sys;

use app\common\basics\Models;

/**
 * 浏览日志模型
 */
class SysVisitor extends Models
{
    public static int $logId = 0;

    // 设置字段信息
    protected $schema = [
        'id'          => 'int',     //主键
        'user_id'     => 'int',     //用户ID
        'module'      => 'string',  //所属模块
        'method'      => 'string',  //请求方式
        'url'         => 'string',  //请求路由
        'ip'          => 'string',  //请求IP
        'ua'          => 'string',  //请求UA
        'browser'     => 'string',  //请求内核
        'params'      => 'string',  //请求参数
        'error'       => 'string',  //错误信息
        'trace'       => 'string',  //错误异常
        'status'      => 'int',     //执行状态: 1=成功, 2=失败
        'start_time'  => 'string',  //开始时间: 毫秒
        'end_time'    => 'string',  //结束时间: 毫秒
        'task_time'   => 'int',     //耗时时间: 毫秒
        'create_time' => 'int'      //操作时间
    ];
}