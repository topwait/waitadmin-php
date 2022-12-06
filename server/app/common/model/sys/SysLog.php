<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/WaitAdmin
// | github:  https://github.com/topwait/waitadmin
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------


namespace app\common\model\sys;


use app\common\basics\Models;

/**
 * 系统日志模型
 *
 * Class SysLog
 * @package app\common\model\sys
 */
class SysLog extends Models
{
    public static int $logId = 0;

    // 设置字段信息
    protected $schema = [
        'id'          => 'int',     //主键
        'admin_id'    => 'string',  //管理员ID
        'method'      => 'string',  //请求方式
        'url'         => 'string',  //请求路由
        'ip'          => 'string',  //请求IP
        'ua'          => 'string',  //请求UA
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