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
            'smtp_type'   => $mail['smtp_type']   ?? '',
            'smtp_host'   => $mail['smtp_host']   ?? '',
            'smtp_port'   => $mail['smtp_port']   ?? '',
            'smtp_user'   => $mail['smtp_user']   ?? '',
            'smtp_pass'   => $mail['smtp_pass']   ?? '',
            'from_user'   => $mail['from_user']   ?? '',
            'verify_type' => $mail['verify_type'] ?? ''
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
        ConfigUtils::set('mail', 'smtp_type', $post['smtp_type'] ?? '', '邮件方式');
        ConfigUtils::set('mail', 'smtp_host', $post['smtp_host'] ?? '', 'SMTP服务');
        ConfigUtils::set('mail', 'smtp_port', $post['smtp_port'] ?? '', 'SMTP端口');
        ConfigUtils::set('mail', 'smtp_user', $post['smtp_user'] ?? '', 'SMTP账号');
        ConfigUtils::set('mail', 'smtp_pass', $post['smtp_pass'] ?? '', 'SMTP密码');
        ConfigUtils::set('mail', 'from_user', $post['from_user'] ?? '', '发件人邮箱');
        ConfigUtils::set('mail', 'verify_type', $post['verify_type'] ?? '', 'SMTP验证');
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