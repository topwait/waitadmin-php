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

    const SENDER_SYS = 1; //短信类型
    const SENDER_EMS = 2; //邮件类型
    const SENDER_SMS = 3; //短信类型
    const SENDER_MNP = 4; //小程序类型
    const SENDER_OA  = 5; //公众号类型

    const SCENE_LOGIN = 101; // 登录手机验证码
}