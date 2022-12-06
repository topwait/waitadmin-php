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


use app\common\basics\Service;
use app\common\exception\SystemException;
use app\common\service\mail\MailDriver;
use app\common\utils\ConfigUtils;

/**
 * 网站配置服务类
 *
 * Class BasicsService
 * @package app\admin\service\setting
 */
class BasicsService extends Service
{
    /**
     * 基本配置详情
     *
     * @return array
     * @author windy
     */
    public static function detail(): array
    {
        // 网站配置
        $website = ConfigUtils::get('website');
        $detail['website'] = [
            'website_title'     => $website['website_title']   ?? '',
            'website_logo'      => $website['website_logo']   ?? '',
            'website_copyright' => $website['website_copyright']   ?? '',
            'website_icp'       => $website['website_icp']     ?? '',
            'website_pcp'       => $website['website_pcp']     ?? '',
            'website_analyse'   => $website['website_analyse'] ?? ''
        ];

        // 邮件配置
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

        // SEO配置
        $seo = ConfigUtils::get('seo');
        $detail['seo'] = [
            'seo_title'       => $seo['seo_title']       ?? '',
            'seo_keywords'    => $seo['seo_keywords']    ?? '',
            'seo_description' => $seo['seo_description'] ?? ''
        ];

        return $detail;
    }

    /**
     * 基本配置保存
     *
     * @param array $post
     * @author windy
     */
    public static function save(array $post): void
    {
        // 网站配置
        ConfigUtils::set('website', 'website_title', $post['website_title'] ?? '', '网站标题');
        ConfigUtils::set('website', 'website_logo', $post['website_logo'] ?? '', '网站logo');
        ConfigUtils::set('website', 'website_copyright', $post['website_copyright'] ?? '', '网站版权');
        ConfigUtils::set('website', 'website_icp', $post['website_icp'] ?? '', 'ICP备案');
        ConfigUtils::set('website', 'website_pcp', $post['website_pcp'] ?? '', '公安备案');
        ConfigUtils::set('website', 'website_analyse', $post['website_analyse'] ?? '', '统计代码');

        // 邮件配置
        ConfigUtils::set('mail', 'mail_type', $post['mail_type'] ?? '', '邮件方式');
        ConfigUtils::set('mail', 'mail_smtp_host', $post['mail_smtp_host'] ?? '', 'SMTP服务');
        ConfigUtils::set('mail', 'mail_smtp_port', $post['mail_smtp_port'] ?? '', 'SMTP端口');
        ConfigUtils::set('mail', 'mail_smtp_user', $post['mail_smtp_user'] ?? '', 'SMTP账号');
        ConfigUtils::set('mail', 'mail_smtp_pass', $post['mail_smtp_pass'] ?? '', 'SMTP密码');
        ConfigUtils::set('mail', 'mail_from_user', $post['mail_from_user'] ?? '', 'SMTP验证');
        ConfigUtils::set('mail', 'mail_verify_type', $post['mail_verify_type'] ?? '', '发件人邮箱');

        // SEO配置
        ConfigUtils::set('seo', 'seo_title', $post['seo_title'] ?? '', 'SEO的标题');
        ConfigUtils::set('seo', 'seo_keywords', $post['seo_keywords'] ?? '', 'SEO关键字');
        ConfigUtils::set('seo', 'seo_description', $post['seo_description'] ?? '', 'SEO的描述');
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