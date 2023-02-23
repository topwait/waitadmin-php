<?php

namespace app\common\service\msg;

use app\common\model\NoticeSetting;
use app\common\service\msg\engine\EmsMsgService;
use app\common\service\msg\engine\SmsMsgService;

/**
 * 通知驱动类
 */
class MsgDriver
{
    /**
     * 发起通知
     *
     * @param array $params 参数
     * @author windy
     */
    public static function send(array $params): void
    {
        $scene = (int)$params['scene'];
        $param = (array)$params['param'];

        $noticeSetting = new NoticeSetting();
        $template = $noticeSetting
            ->where(['is_delete'=>0])
            ->where(['scene'=>$scene])
            ->findOrEmpty()
            ->toArray();

        if (isset($template['ems_template']['status']) and $template['ems_template']['status'] == 1) {
            (new EmsMsgService())->send($scene, $param, $template);
        }

        if (isset($template['sms_template']['status']) and $template['sms_template']['status'] == 1) {
            (new SmsMsgService())->send($scene, $param, $template);
        }
    }

}