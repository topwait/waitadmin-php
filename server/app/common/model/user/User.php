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


namespace app\common\model\user;


use app\common\basics\Models;
use app\common\utils\UrlUtils;

/**
 * 用户模型
 */
class User extends Models
{
    // 设置字段信息
    protected $schema = [
        'id'              => 'int',     //主键
        'group_id'        => 'int',     //分组
        'sn'              => 'string',  //编号
        'avatar'          => 'string',  //用户头像
        'account'         => 'string',  //用户账号
        'nickname'        => 'string',  //用户昵称
        'password'        => 'string',  //登录密码
        'sign'            => 'string',  //个性签名
        'salt'            => 'string',  //加密盐巴
        'gender'          => 'int',     //用户性别
        'mobile'          => 'string',  //电话号码
        'email'           => 'string',  //电子邮箱
        'last_login_ip'   => 'string',  //最后登录IP
        'last_login_time' => 'int',     //最后登录时间
        'is_disable'      => 'int',     //是否删除: [0=否, 1=是]
        'is_delete'       => 'int',     //是否删除: [0=否, 1=是]
        'create_time'     => 'int',     //创建时间
        'update_time'     => 'int',     //更新时间
        'delete_time'     => 'int'      //删除时间
    ];

    /**
     * 头像路径转绝对
     *
     * @param $value
     * @return string
     */
    public function getAvatarAttr($value): string
    {
        if ($value) {
            return UrlUtils::toAbsoluteUrl($value);
        }
        return '';
    }
}