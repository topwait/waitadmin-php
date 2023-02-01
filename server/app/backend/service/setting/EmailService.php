<?php

namespace app\backend\service\setting;

use app\common\basics\Backend;
use app\common\exception\SystemException;
use app\common\service\mail\MailDriver;
use app\common\utils\ConfigUtils;

class EmailService extends Backend
{
    /**
     * 邮件配置详情
     *
     * @return array
     * @author windy
     */
    public static function detail(): array
    {
        $mail = ConfigUtils::get('mail');
        $detail['mail'] = [
            'smtpType'   => $mail['smtpType']   ?? '',
            'smtpHost'   => $mail['smtpHost']   ?? '',
            'smtpPort'   => $mail['smtpPort']   ?? '',
            'smtpUser'   => $mail['smtpUser']   ?? '',
            'smtpPass'   => $mail['smtpPass']   ?? '',
            'fromUser'   => $mail['fromUser']   ?? '',
            'verifyType' => $mail['verifyType'] ?? ''
        ];

        return $detail['mail'];
    }

    /**
     * 邮件配置保存
     *
     * @param array $post
     * @author windy
     */
    public static function save(array $post): void
    {
        ConfigUtils::set('mail', 'smtpType', $post['smtpType'] ?? '', '邮件方式');
        ConfigUtils::set('mail', 'smtpHost', $post['smtpHost'] ?? '', 'SMTP服务');
        ConfigUtils::set('mail', 'smtpPort', $post['smtpPort'] ?? '', 'SMTP端口');
        ConfigUtils::set('mail', 'smtpUser', $post['smtpUser'] ?? '', 'SMTP账号');
        ConfigUtils::set('mail', 'smtpPass', $post['smtpPass'] ?? '', 'SMTP密码');
        ConfigUtils::set('mail', 'fromUser', $post['fromUser'] ?? '', '发件人邮箱');
        ConfigUtils::set('mail', 'verifyType', $post['verifyType'] ?? '', 'SMTP验证');
    }

    /**
     * 邮件测试发送
     *
     * @param string $recipient
     * @throws SystemException
     * @author windy
     */
    public static function testEmail(string $recipient)
    {
        try {
            $mailDriver = new MailDriver();
            $mailDriver
                ->addAddress($recipient)
                ->subject('邮件测试发送标题')
                ->body('邮件测试内容~~~')
                ->send();
        } catch (\Exception $e) {
            throw new SystemException(mb_substr($e->getMessage(), 0, 40));
        }
    }
}