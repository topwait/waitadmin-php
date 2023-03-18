<?php

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