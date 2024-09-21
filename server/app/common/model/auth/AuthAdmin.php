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

namespace app\common\model\auth;

use app\common\basics\Models;
use app\common\utils\UrlUtils;
use think\model\relation\HasOne;

/**
 * 管理员模型
 */
class AuthAdmin extends Models
{
    // 设置字段信息
    protected $schema = [
        'id'              => 'int',     //用户主键
        'role_id'         => 'int',     //角色主键
        'dept_id'         => 'int',     //部门主键
        'post_id'         => 'int',     //岗位主键
        'nickname'        => 'string',  //账号昵称
        'username'        => 'string',  //登录账号
        'password'        => 'string',  //登录密码
        'salt'            => 'string',  //加密盐巴
        'avatar'          => 'string',  //用户头像
        'phone'           => 'string',  //用户电话
        'email'           => 'string',  //电子邮箱
        'last_login_ip'   => 'string',  //登录地址
        'last_login_time' => 'int',     //登录时间
        'is_disable'      => 'int',     //是否禁用: [0=否, 1=是]
        'is_delete'       => 'int',     //是否禁用: [0=否, 1=是]
        'create_time'     => 'int',     //创建时间
        'update_time'     => 'int',     //更新时间
        'delete_time'     => 'int',     //删除时间
    ];

    /**
     * 单关联角色模型
     *
     * @author zero
     * @return HasOne
     */
    public function role(): HasOne
    {
        return $this->hasOne(AuthRole::class, 'id', 'role_id')
            ->field('id,name');
    }

    /**
     * 单关联部门模型
     *
     * @author zero
     * @return HasOne
     */
    public function dept(): HasOne
    {
        return $this->hasOne(AuthDept::class, 'id', 'dept_id')
            ->field('id,pid,name,duty,mobile');
    }

    /**
     * 单关联岗位模型
     *
     * @author zero
     * @return HasOne
     */
    public function post(): HasOne
    {
        return $this->hasOne(AuthPost::class, 'id', 'post_id')
            ->field('id,code,name');
    }

    /**
     * 获取器：处理头像路径
     *
     * @param $value
     * @return string
     * @author zero
     */
    public function getAvatarAttr($value): string
    {
        if (!$value) {
            return '';
        } else {
            return UrlUtils::toAbsoluteUrl($value);
        }
    }
}