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
            'title'     => $website['title']   ?? '',
            'favicon'   => $website['favicon'] ?? '',
            'copyright' => $website['copyright'] ?? '',
            'icp'       => $website['icp']     ?? '',
            'pcp'       => $website['pcp']     ?? '',
            'analyse'   => $website['analyse'] ?? ''
        ];

        // 登录配置
        $login = ConfigUtils::get('login');
        $detail['login'] = [
            'forceMobile' => intval($login['forceMobile'] ?? 0),
            'loginModes'  => $login['loginModes'] ?? [],
            'loginOther'  => $login['loginOther'] ?? [],
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
        ConfigUtils::set('website', 'favicon', $post['websiteFavicon'] ?? '', '网站图标');
        ConfigUtils::set('website', 'title', $post['websiteTitle'] ?? '', '网站标题');
        ConfigUtils::set('website', 'copyright', $post['websiteCopyright'] ?? '', '网站版权');
        ConfigUtils::set('website', 'icp', $post['websiteIcp'] ?? '', 'ICP备案');
        ConfigUtils::set('website', 'pcp', $post['websitePcp'] ?? '', '公安备案');
        ConfigUtils::set('website', 'analyse', $post['websiteAnalyse'] ?? '', '统计代码');

        // 登录配置
        ConfigUtils::set('login', 'forceMobile', $post['forceMobile'] ?? 0, '强制绑定手机');
        ConfigUtils::set('login', 'loginModes', json_encode($post['loginModes'] ?? []), '通用登录方式');
        ConfigUtils::set('login', 'loginOther', json_encode($post['loginOther'] ?? []), '第三方登录');
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