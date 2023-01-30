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
            'mail_type'        => $mail['mail_type']        ?? '',
            'mail_smtp_host'   => $mail['mail_smtp_host']   ?? '',
            'mail_smtp_port'   => $mail['mail_smtp_port']   ?? '',
            'mail_smtp_user'   => $mail['mail_smtp_user']   ?? '',
            'mail_smtp_pass'   => $mail['mail_smtp_pass']   ?? '',
            'mail_from_user'   => $mail['mail_from_user']   ?? '',
            'mail_verify_type' => $mail['mail_verify_type'] ?? ''
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
        ConfigUtils::set('mail', 'mail_type', $post['mail_type'] ?? '', '邮件方式');
        ConfigUtils::set('mail', 'mail_smtp_host', $post['mail_smtp_host'] ?? '', 'SMTP服务');
        ConfigUtils::set('mail', 'mail_smtp_port', $post['mail_smtp_port'] ?? '', 'SMTP端口');
        ConfigUtils::set('mail', 'mail_smtp_user', $post['mail_smtp_user'] ?? '', 'SMTP账号');
        ConfigUtils::set('mail', 'mail_smtp_pass', $post['mail_smtp_pass'] ?? '', 'SMTP密码');
        ConfigUtils::set('mail', 'mail_from_user', $post['mail_from_user'] ?? '', 'SMTP验证');
        ConfigUtils::set('mail', 'mail_verify_type', $post['mail_verify_type'] ?? '', '发件人邮箱');
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