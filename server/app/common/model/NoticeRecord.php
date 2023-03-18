<?php

namespace app\common\model;

use app\common\basics\Models;

/**
 * 通知记录模型
 */
class NoticeRecord extends Models
{
    // 设置字段信息
    protected $schema = [
        'id'           => 'int',    //主键ID
        'scene'        => 'int',    //通知场景
        'user_id'      => 'int',    //接收用户
        'account'      => 'string', //接收账号
        'title'        => 'string', //通知标题
        'code'         => 'string', //验证编码
        'content'      => 'string', //通知内容
        'error'        => 'string', //失败原因
        'sender'       => 'int',    //发送类型: [1=系统, 2=邮件, 3=短信, 4=公众号, 5=小程序]
        'receiver'     => 'int',    //接收对象: [1=用户, 2=平台]
        'status'       => 'int',    //通知状态: [0=等待, 1=成功, 2=失败]
        'is_read'      => 'int',    //已读状态: [0=未读, 1=已读]
        'is_captcha'   => 'int',    //是验证码: [0=否的, 1=是的]
        'is_delete'    => 'int',    //是否删除: [0=否的, 1=是的]
        'expire_time'  => 'int',    //失效时间
        'create_time'  => 'int',    //创建时间
        'update_time'  => 'int',    //更新时间
        'delete_time'  => 'int'     //删除时间
    ];

}