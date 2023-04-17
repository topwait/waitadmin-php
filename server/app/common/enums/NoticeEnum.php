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

namespace app\common\enums;

/**
 * 通知枚举类
 */
class NoticeEnum
{
    const STATUS_WAIT = 0; //等待
    const STATUS_OK   = 1; //成功
    const STATUS_FAIL = 2; //失败

    const VIEW_UNREAD = 0; //未读
    const VIEW_READ   = 1; //已读

    const SENDER_SYS = 1; //系统类型
    const SENDER_EMS = 2; //邮件类型
    const SENDER_SMS = 3; //短信类型
    const SENDER_MNP = 4; //小程序类型
    const SENDER_OA  = 5; //公众号类型

    const LOGIN         = 101; // 免密登录验证码
    const REGISTER      = 102; // 账号注册验证码
    const FORGET_PWD    = 103; // 找回密码验证码
    const CHANGE_MOBILE = 104; // 变更手机验证码
    const BIND_MOBILE   = 105; // 绑定手机验证码
    const BIND_EMAIL    = 106; // 绑定邮箱验证码

}