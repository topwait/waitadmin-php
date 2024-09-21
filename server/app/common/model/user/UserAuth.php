<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/waitadmin-php
// | github:  https://github.com/topwait/waitadmin-php
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\user;

use app\common\basics\Models;

/**
 * 用户授权模型
 */
class UserAuth extends Models
{
    protected $schema = [
        'id'          => 'int',     //主键
        'user_id'     => 'int',     //用户主键
        'openid'      => 'string',  //OpenID
        'unionid'     => 'string',  //UnionId
        'terminal'    => 'int',     //客户端[1=微信小程序, 2=微信公众号, 3=H5, 4=PC, 5=安卓, 6=苹果]
        'create_time' => 'int',     //创建时间
        'update_time' => 'int',     //更新时间
    ];
}