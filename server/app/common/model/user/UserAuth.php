<?php

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