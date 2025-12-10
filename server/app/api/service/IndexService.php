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

namespace app\api\service;

use app\common\basics\Service;
use app\common\model\article\Article;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;

/**
 * 主页服务类
 */
class IndexService extends Service
{
    /**
     * 首页数据
     *
     * @return array
     * @author zero
     */
    public static function index(): array
    {
        // 文章列表
        $modelArticle = new Article();
        $detail['article'] = $modelArticle
            ->field(['id,title,image,intro,browse,create_time'])
            ->where(['is_show'=>1])
            ->where(['is_delete'=>0])
            ->order('is_recommend desc, id desc')
            ->limit(15)
            ->select()
            ->toArray();

        return $detail;
    }

    /**
     * 全局配置
     *
     * @return array
     * @author zero
     */
    public static function config(): array
    {
        // 登录配置
        $loginConfig = ConfigUtils::get('login');
        $detail['login'] = [
            // 微信端
            'wx' => [
                'is_agreement'   => boolval($loginConfig['wx']['is_agreement'] ?? 0),
                'force_mobile'   => boolval($loginConfig['wx']['force_mobile'] ?? 0),
                'default_method' => $loginConfig['wx']['default_method'] ?? '',
                'usable_channel' => $loginConfig['wx']['usable_channel'] ?? []
            ],
            // PC端
            'pc' => [
                'is_agreement'   => boolval($loginConfig['pc']['is_agreement'] ?? 0),
                'force_mobile'   => boolval($loginConfig['pc']['force_mobile'] ?? 0),
                'default_method' => $loginConfig['pc']['default_method'] ?? '',
                'usable_channel' => $loginConfig['pc']['usable_channel'] ?? []
            ],
            // H5端
            'h5' => [
                'is_agreement'   => boolval($loginConfig['h5']['is_agreement'] ?? 0),
                'force_mobile'   => boolval($loginConfig['h5']['force_mobile'] ?? 0),
                'default_method' => $loginConfig['h5']['default_method'] ?? '',
                'usable_channel' => $loginConfig['h5']['usable_channel'] ?? [],
            ],
            // 其它端
            'other' => [
                'is_agreement'   => boolval($loginConfig['other']['is_agreement'] ?? 0),
                'force_mobile'   => boolval($loginConfig['other']['force_mobile'] ?? 0),
                'default_method' => $loginConfig['other']['default_method'] ?? '',
                'usable_channel' => $loginConfig['other']['usable_channel'] ?? []
            ],
            // 基础配置
            'basis' => [
                'logo' => UrlUtils::toAbsoluteUrl($loginConfig['basis']['logo'] ?? ''),
                'tips' => $loginConfig['basis']['tips'] ?? ''
            ]
        ];

        // H5配置
        $h5Config = ConfigUtils::get('h5');
        $detail['h5'] = [
            'title'     => $h5Config['title']??'',
            'logo'      => UrlUtils::toAbsoluteUrl($h5Config['logo']??''),
            'status'    => intval($h5Config['status']??0),
            'close_url' => strval($h5Config['close_url']??'')
        ];
        return $detail;
    }

    /**
     * 政策协议
     *
     * @param string $type (类型: service/privacy)
     * @return array
     * @author zero
     */
    public static function policy(string $type): array
    {
        $value = ConfigUtils::get('policy', $type, '');
        return ['content'=>$value] ?? [];
    }

    /**
     * 装修数据
     *
     * @return array
     * @author zero
     */
    public static function decorate(): array
    {
        $themeConfig = ConfigUtils::get('diy', 'theme');
        return [
            'color'  => $themeConfig['color'] ?? '',
            'theme'  => $themeConfig['subject'] ?? '',
            'tabbar' => DiyService::tabbar(),
            'homing' => DiyService::homing(),
            'myself' => DiyService::myself(),
            'tie'    => DiyService::tie()
        ];
    }
}