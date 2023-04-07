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

namespace app\common\model;

use app\common\basics\Models;

/**
 * 通知设置模型
 */
class NoticeSetting extends Models
{
    // 设置字段信息
    protected $schema = [
        'id'           => 'int',    //主键ID
        'scene'        => 'int',    //场景编号
        'name'         => 'string', //场景名称
        'remarks'      => 'string', //场景描述
        'variable'     => 'string', //场景变量
        'sys_template' => 'string', //系统通知模板
        'sms_template' => 'string', //短信通知模板
        'ems_template' => 'string', //邮件通知模板
        'get_client'   => 'int',    //接收端口: [1=用户, 2=平台]
        'is_captcha'   => 'int',    //是验证码: [0=否的, 1=是的]
        'is_delete'    => 'int',    //是否删除: [0=否的, 1=是的]
        'create_time'  => 'int',    //创建时间
        'update_time'  => 'int',    //更新时间
        'delete_time'  => 'int'     //删除时间
    ];

    /**
     * 获取器: 修改变量参数格式
     *
     * @param $value
     * @return array
     * @author zero
     */
    public function getVariableAttr($value): array
    {
        if ($value) {
            return (array) json_decode($value, true);
        }

        return [];
    }

    /**
     * 获取器: 修改系统模板格式
     *
     * @param $value
     * @return array
     * @author zero
     */
    public function getSysTemplateAttr($value): array
    {
        if ($value) {
            return (array) json_decode($value, true);
        }

        return [];
    }

    /**
     * 获取器: 修改邮件模板格式
     *
     * @param $value
     * @return array
     * @author zero
     */
    public function getEmsTemplateAttr($value): array
    {
        if ($value) {
            return (array) json_decode($value, true);
        }

        return [];
    }

    /**
     * 获取器: 修改短信模板格式
     *
     * @param $value
     * @return array
     * @author zero
     */
    public function getSmsTemplateAttr($value): array
    {
        if ($value) {
            return (array) json_decode($value, true);
        }

        return [];
    }
}