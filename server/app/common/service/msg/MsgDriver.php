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
declare (strict_types = 1);

namespace app\common\service\msg;

use app\common\enums\NoticeEnum;
use app\common\model\notice\NoticeRecord;
use app\common\model\notice\NoticeSetting;
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
     * @author zero
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
     * @param bool $use
     * @return bool
     */
    public static function checkCode(int $scene, string $code, bool $use=false): bool
    {
        $modelNoticeRecord = new NoticeRecord();
        $noticeRecord = $modelNoticeRecord
            ->field(['id,scene,code,expire_time'])
            ->where(['scene'=>$scene])
            ->where(['status'=>NoticeEnum::STATUS_OK])
            ->where(['is_read'=>NoticeEnum::VIEW_UNREAD])
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

        if ($use) {
            NoticeRecord::update([
                'is_read' => NoticeEnum::VIEW_READ,
                'update_time' => time()
            ], ['id' => $noticeRecord['id']]);
        }

        return $result;
    }

    /**
     * 消费验证码
     *
     * @param int $scene
     * @param string $code
     * @author zero
     */
    public static function useCode(int $scene, string $code): void
    {
        $modelNoticeRecord = new NoticeRecord();
        $noticeRecord = $modelNoticeRecord
            ->field(['id,scene,code,expire_time'])
            ->where(['scene'=>$scene])
            ->where(['status'=>NoticeEnum::STATUS_OK])
            ->where(['is_captcha'=>1])
            ->where(['code'=>$code])
            ->findOrEmpty()
            ->toArray();

        if ($noticeRecord) {
            NoticeRecord::update([
                'is_read' => NoticeEnum::VIEW_READ,
                'update_time' => time()
            ], ['id' => $noticeRecord['id']]);
        }
    }
}