<?php

namespace app\common\service\msg\engine;

use app\common\enums\NoticeEnum;
use app\common\model\NoticeRecord;

class EmsMsgService
{
    public function send(int $scene, array $params, array $template)
    {
        // 创建发送记录
        $noticeRecord = NoticeRecord::create([
            'scene'       => $scene,
            'user_id'     => $params['user_id']??0,
            'account'     => $params['email'],
            'title'       => $template['name'],
            'code'        => $this->getCode($params, $template),
            'content'     => $this->getContent($params, $template),
            'receiver'    => $template['get_client'],
            'sender'      => NoticeEnum::SENDER_EMS,
            'status'      => NoticeEnum::STATUS_WAIT,
            'is_read'     => NoticeEnum::VIEW_UNREAD,
            'is_captcha'  => $template['is_captcha'],
            'expire_time' => $params['expire_time']??0,
            'create_time' => time(),
            'update_time' => time()
        ]);
    }
}