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
 * 客服装修服务类
 */
class ContactService extends Service
{
    /**
     * 客服装修详情
     *
     * @return array
     * @author zero
     */
    public static function detail(): array
    {
        $detail = ConfigUtils::get('diy', 'contact', [
            'title'    => '',
            'datetime' => '',
            'mobile'   => '',
            'qq'       => '',
            'image'    => ''
        ]);

        $detail['image'] = UrlUtils::toAbsoluteUrl($detail['image']??'');
        return (array) $detail;
    }

    /**
     * 客服装修保存
     *
     * @param array $post
     * @author zero
     */
    public static function save(array $post): void
    {
        ConfigUtils::set('diy', 'contact', [
            'title'    => $post['title']??'',
            'datetime' => $post['datetime']??'',
            'mobile'   => $post['mobile']??'',
            'qq'       => $post['qq']??'',
            'image'    => UrlUtils::toRelativeUrl($post['image']??'')
        ]);
    }
}