<?php

namespace app\common\service\msg;

use app\common\model\NoticeRecord;
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
     * @param int $scene    (场景)
     * @param array $params (参数)
     * @author windy
     */
    public static function send(int $scene, array $params=[]): void
    {
        $noticeSetting = new NoticeSetting();
        $template = $noticeSetting
            ->where(['is_delete'=>0])
            ->where(['scene'=>$scene])
            ->findOrEmpty()
            ->toArray();

        if (isset($template['ems_template']['status']) and $template['ems_template']['status'] == 1) {
            (new EmsMsgService())->send($scene, $params, $template);
        }

        if (isset($template['sms_template']['status']) and $template['sms_template']['status'] == 1) {
            (new SmsMsgService())->send($scene, $params, $template);
        }
    }

    /**
     * 验证Code
     *
     * @param int $scene
     * @param string $code
     * @return bool
     */
    public static function checkCode(int $scene, string $code): bool
    {
        $modelNoticeRecord = new NoticeRecord();
        $noticeRecord = $modelNoticeRecord->field(['id,scene,code,expire_time'])
            ->where(['scene'=>$scene])
            ->where(['status'=>1])
            ->where(['is_read'=>0])
            ->where(['is_captcha'=>1])
            ->where(['is_delete'=>0])
            ->where(['code'=>$code])
            ->findOrEmpty()
            ->toArray();

        if (!$noticeRecord) {
            return false;
        }

        $result = true;
        if ($noticeRecord['expire_time'] <= time()) {
            $result = false;
        }

        NoticeRecord::update([
            'is_read'     => 1,
            'update_time' => time()
        ], ['id'=>$noticeRecord['id']]);

        return $result;
    }

}