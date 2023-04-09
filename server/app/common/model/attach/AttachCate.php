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

namespace app\common\model\attach;

use app\common\basics\Models;

/**
 * 附件分类模型
 */
class AttachCate extends Models
{
    // 设置字段信息
    protected $schema = [
        'id'              => 'int',     //主键
        'name'            => 'string',  //分类名称
        'type'            => 'int',     //分类类型: [10=图片, 20=视频]
        'is_delete'       => 'int',     //是否禁用: [0=否, 1=是]
        'create_time'     => 'int',     //创建时间
        'update_time'     => 'int',     //更新时间
        'delete_time'     => 'int'      //删除时间
    ];
}