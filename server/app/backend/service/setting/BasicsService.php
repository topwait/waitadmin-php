<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/waitadmin-php
// | github:  https://github.com/topwait/waitadmin-php
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\backend\service\setting;

use app\common\basics\Service;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;
use Exception;

/**
 * 网站配置服务类
 */
class BasicsService extends Service
{
    /**
     * 基本配置详情
     *
     * @return array
     * @author zero
     */
    public static function detail(): array
    {
        // 基础配置
        $website = ConfigUtils::get('website');
        $detail['website'] = [
            'copyright' => $website['copyright']??'',
            'icp'       => $website['icp']??'',
            'pcp'       => $website['pcp']??'',
            'analyse'   => $website['analyse']??''
        ];

        // PC端配置
        $pc = ConfigUtils::get('pc');
        $detail['pc'] = [
            'title'       => $pc['title']??'',
            'keywords'    => $pc['keywords']??'',
            'description' => $pc['description']??'',
            'logo'        => UrlUtils::toAbsoluteUrl(strval($pc['logo']??'')),
        ];

        // H5端配置
        $h5 = ConfigUtils::get('h5');
        $detail['h5'] = [
            'title'     => $h5['title']??'',
            'logo'      => UrlUtils::toAbsoluteUrl(strval($h5['logo']??'')),
            'status'    => intval($h5['status']??0),
            'close_url' => strval($h5['close_url']??''),
        ];

        return $detail;
    }

    /**
     * 基本配置保存
     *
     * @param array $post
     * @throws Exception
     * @author zero
     */
    public static function save(array $post): void
    {
        // 基础配置
        ConfigUtils::setItem('website', [
            'icp'       => $post['website_icp']??'',
            'pcp'       => $post['website_pcp']??'',
            'analyse'   => $post['website_analyse']??'',
            'copyright' => $post['website_copyright']??''
        ]);

        // PC端配置
        ConfigUtils::setItem('pc', [
            'title'       => $post['pc_title']??'',
            'keywords'    => $post['pc_keywords']??'',
            'description' => $post['pc_description']??'',
            'logo' => UrlUtils::toRelativeUrl($post['pc_logo']??'')
        ]);

        // H5端配置
        ConfigUtils::setItem('h5', [
            'title'     => $post['h5_title']??'',
            'logo'      => UrlUtils::toRelativeUrl($post['h5_logo']??''),
            'status'    => $post['h5_status']??0,
            'close_url' => $post['h5_close_url']??'',
        ]);
    }
}