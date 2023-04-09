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
 * 系统计划任务模型
 */
class SysCrontab extends Models
{
    // 设置字段信息
    protected $schema = [
        'id'          => 'int',     //主键
        'name'        => 'string',  //任务名称
        'command'     => 'string',  //执行命令
        'params'      => 'string',  //附带参数
        'rules'       => 'string',  //运行规则
        'remarks'     => 'string',  //备注信息
        'error'       => 'string',  //错误提示
        'status'      => 'int',     //执行状态: [1=运行, 2=暂停, 3=错误]
        'exe_time'    => 'int',     //执行时长
        'max_time'    => 'int',     //最大执行时长
        'last_time'   => 'int',     //最后执行时间
        'create_time' => 'int',     //创建时间
        'update_time' => 'int'      //更新时间
    ];

    /**
     * 获取器: 格式化最后执行时间
     *
     * @author zero
     * @param $value
     * @return string
     */
    public function getLastTimeAttr($value): string
    {
        return date('Y-m-d H:i:s', $value);
    }
}