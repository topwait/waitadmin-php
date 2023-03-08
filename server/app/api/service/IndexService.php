<?php

namespace app\api\service;

use app\common\basics\Service;
use app\common\model\content\Article;
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
     * @author windy
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
     * @author windy
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

        // 底部导航
        $detail['tabBar'] = [
            'style' => ConfigUtils::get('diy', 'tab_bar_style', []),
            'list'  => ConfigUtils::get('diy', 'tab_bar_list', [])
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
     * @return array
     * @author windy
     */
    public static function policy(): array
    {
        return [];
    }
}