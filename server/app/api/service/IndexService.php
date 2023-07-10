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

namespace app\api\service;

use app\common\basics\Service;
use app\common\model\article\Article;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 主页服务类
 */
class IndexService extends Service
{
    /**
     * 首页数据
     *
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
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
        $loginOther  = array_map(function ($val) {return intval($val);}, $loginConfig['login_other']??[]);
        if (in_array('1', $loginConfig['login_modes']??[])) $loginModes[] = ['alias'=>'account', 'name'=>'账号登录'];
        if (in_array('2', $loginConfig['login_modes']??[])) $loginModes[] = ['alias'=>'mobile', 'name'=>'免密登录'];
        $detail['login'] = [
            'is_agreement' => intval($loginConfig['is_agreement']??0),
            'force_mobile' => intval($loginConfig['force_mobile']??0),
            'auths_mobile' => intval($loginConfig['auths_mobile']??0),
            'login_modes'  => $loginModes??[],
            'login_other'  => $loginOther??[],
        ];

        // H5配置
        $h5Config = ConfigUtils::get('h5');
        $detail['h5'] = [
            'title'     => $h5Config['title']??'',
            'logo'      => UrlUtils::toAbsoluteUrl($h5Config['logo']??''),
            'status'    => intval($h5Config['status']??0),
            'close_url' => strval($h5Config['close_url']??'')
        ];

        // 主题风格
        $themeConfig = ConfigUtils::get('diy', 'theme');
        $detail['theme'] = [
            'subject'         => $themeConfig['subject']         ?? '',
            'frontColor'      => $themeConfig['frontColor']      ?? null,
            'backgroundColor' => $themeConfig['backgroundColor'] ?? null,
        ];

        // 底部导航
        $tabBar = ConfigUtils::get('diy', 'tabbar', []);
        $detail['tabBar'] = [
            'style' => [
                'selectedColor'   => $tabBar['style']['selectedColor'] ?? '#2979ff',
                'unselectedColor' => $tabBar['style']['unselectedColor'] ?? '##333333'
            ],
            'list' => $tabBar['list'] ?? []
        ];
        foreach ($detail['tabBar']['list'] as &$item) {
            $item['iconPath'] = UrlUtils::toAbsoluteUrl($item['iconPath']??'');
            $item['selectedIconPath'] = UrlUtils::toAbsoluteUrl($item['selectedIconPath']??'');
        }

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
}