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
            'website_favicon'   => $website['website_favicon'] ?? '',
            'website_copyright' => $website['website_copyright'] ?? '',
            'website_icp'       => $website['website_icp']     ?? '',
            'website_pcp'       => $website['website_pcp']     ?? '',
            'website_analyse'   => $website['website_analyse'] ?? ''
        ];

        // SEO配置
        $login = ConfigUtils::get('login');
        $detail['login'] = [
            'force_mobile' => $login['force_mobile'] ?? '',
            'login_modes'  => json_decode($login['login_modes']??'[]', true),
            'login_other'  => json_decode($login['login_other']??'[]', true),
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
        ConfigUtils::set('website', 'website_favicon', $post['website_favicon'] ?? '', '网站图标');
        ConfigUtils::set('website', 'website_title', $post['website_title'] ?? '', '网站标题');
        ConfigUtils::set('website', 'website_copyright', $post['website_copyright'] ?? '', '网站版权');
        ConfigUtils::set('website', 'website_icp', $post['website_icp'] ?? '', 'ICP备案');
        ConfigUtils::set('website', 'website_pcp', $post['website_pcp'] ?? '', '公安备案');
        ConfigUtils::set('website', 'website_analyse', $post['website_analyse'] ?? '', '统计代码');

        // 登录配置
        ConfigUtils::set('login', 'force_mobile', $post['force_mobile'] ?? 0, '强制绑定手机');
        ConfigUtils::set('login', 'login_modes', json_encode($post['login_modes'] ?? []), '通用登录方式');
        ConfigUtils::set('login', 'login_other', json_encode($post['login_other'] ?? []), '第三方登录');
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