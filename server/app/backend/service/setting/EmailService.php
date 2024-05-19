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

namespace app\backend\service\setting;

use app\common\basics\Backend;
use app\common\exception\SystemException;
use app\common\service\mail\MailDriver;
use app\common\utils\ConfigUtils;
use Exception;

/**
 * 邮件配置服务类
 */
class EmailService extends Backend
{
    /**
     * 邮件配置详情
     *
     * @return array
     * @author zero
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
     * @author zero
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
     * @author zero
     */
    public static function testEmail(string $recipient): void
    {
        try {
            $mailDriver = new MailDriver();
            $mailDriver
                ->addAddress($recipient)
                ->subject('邮件测试发送标题')
                ->body('邮件测试内容~~~')
                ->send();
        } catch (Exception $e) {
            throw new SystemException(mb_substr($e->getMessage(), 0, 40));
        }
    }
}