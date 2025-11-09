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
//        $loginOther  = array_map(function ($val) {return intval($val);}, $loginConfig['login_other']??[]);
//        if (in_array('2', $loginConfig['login_modes']??[])) $loginModes[] = ['alias'=>'account', 'name'=>'账号登录'];
//        if (in_array('1', $loginConfig['login_modes']??[])) $loginModes[] = ['alias'=>'mobile', 'name'=>'免密登录'];
        $detail['login'] = [
            // 显示协议条款
            'is_agreement' => boolval($loginConfig['is_agreement']??0),
            // 强制绑定手机
            'force_mobile' => boolval($loginConfig['force_mobile']??0),
            // 默认登录方式
            'login_method' => 'account',
            // 可用登录渠道
            'login_channel' => []
//            'auths_mobile' => intval($loginConfig['auths_mobile']??0),
//            'login_modes'  => $loginModes??[],
//            'login_other'  => $loginOther,
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
            'subject' => $themeConfig['subject'] ?? '',
            'color'   => $themeConfig['color']   ?? ''
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
        return [
            'theme'  => 'default',
            'tabbar' => DiyService::tabbar(),
            'homing' => DiyService::homing(),
            'myself' => DiyService::myself()
        ];
    }
}