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

namespace app\backend\service\diy;

use app\common\basics\Service;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;

/**
 * 个人中心装修服务类
 */
class PersonService extends Service
{
    /**
     * 装修数据详情
     *
     * @return array
     * @author windy
     */
    public static function detail(): array
    {
        $detail = ConfigUtils::get('diy', 'person');
        if (!$detail) {
            return  [
                'base' => ['layout'=>'row', 'title'=>''],
                'list' => []
            ];
        }

        foreach ($detail['list'] as &$item) {
            $item['image'] = UrlUtils::toAbsoluteUrl($item['image']??'');
        }

        return $detail;
    }

    /**
     * 装修数据保存
     *
     * @param array $post
     * @author windy
     */
    public static function save(array $post): void
    {
        $list = $post['list']??[];
        foreach ($list as &$item) {
            $item['image'] = UrlUtils::toAbsoluteUrl($item['image']);
        }

        ConfigUtils::set('diy', 'person', [
            'style' => [
                'layout' => $post['layout']??'row',
                'title'  => $post['title']??''
            ],
            'list' => $list
        ]);
    }
}