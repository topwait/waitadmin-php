<?php

namespace app\common\service\msg\engine;

use app\common\enums\NoticeEnum;
use app\common\exception\SystemException;
use app\common\model\NoticeRecord;
use app\common\service\mail\MailDriver;
use Exception;

/**
 * 邮件消息服务
 */
class EmsMsgService
{
    /**
     * 发送邮件通知
     *
     * @param array $params   (参数)
     * @param array $template (模板)
     * @author zero
     */
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

        // 发送邮件通知
        try {
            $mailDriver = new MailDriver();
            $mailDriver
                ->addAddress($params['email'])
                ->subject($template['ems_template']['title']??'通知')
                ->body($this->getContent($params, $template))
                ->send();

            NoticeRecord::update([
                'status'      => NoticeEnum::STATUS_OK,
                'update_time' => time()
            ], ['id'=>$noticeRecord['id']]);
        } catch (Exception $e) {
            NoticeRecord::update([
                'status'      => NoticeEnum::STATUS_FAIL,
                'error'       => mb_substr($e->getMessage(), 0, 40),
                'update_time' => time()
            ], ['id'=>$noticeRecord['id']]);
        }
    }

    /**
     * 获取邮件内容(替换模板变量)
     *
     * @param array $params
     * @param array $template
     * @return string
     * @author zero
     */
    private function getContent(array $params, array $template): string
    {
        $content = $template['ems_template']['content']??'';
        foreach ($params as $item => $val) {
            $search_replace = '{' . $item . '}';
            $content = str_replace($search_replace, $val, $content);
        }
        return (string)$content;
    }

    /**
     * 获取邮件验证码(某些场景不需要)
     *
     * @param $params
     * @param $template
     * @return string
     * @author zero
     */
    private function getCode($params, $template): string
    {
        $code = '';
        if ($template['is_captcha']) {
            $code = array_intersect_key($params, $template['variable']);
            if ($code) {
                return array_shift($code);
            }
        }
        return (string)$code;
    }
}