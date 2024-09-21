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
     * @author zero
     */
    public static function detail(): array
    {
        $data = [];
        $detail = ConfigUtils::get('diy', 'person');
        foreach ($detail as $type => $item) {
            $data[$type]['base'] = match ($type) {
                'service' => [
                    'layout' => $item['base']['layout'] ?? 'row',
                    'title'  => $item['base']['title'] ?? '',
                    'number' => $item['base']['number'] ?? 4,
                ],
                'adv' => [
                    'open' => intval($item['base']['open'] ?? '0')
                ],
            };

            foreach ($item['list'] as $value) {
                $data[$type]['list'][] = [
                    'image' => UrlUtils::toAbsoluteUrl($value['image']??''),
                    'name'  => $value['name']??'',
                    'link'  => $value['link']??'',
                ];
            }
        }

        $themeColor = ConfigUtils::get('diy', 'theme');
        $data['themeColor'] = $themeColor['color'] ?? '#2979ff';

        return $data;
    }

    /**
     * 装修数据保存
     *
     * @param array $params
     * @author zero
     */
    public static function save(array $params): void
    {
        $data = [];
        foreach ($params as $type => $item) {
            $data[$type]['base'] = match ($type) {
                'service' => [
                    'layout' => $item['base']['layout'] ?? 'row',
                    'number'  => $item['base']['number'] ?? 4,
                    'title'  => $item['base']['title'] ?? ''
                ],
                'adv' => [
                    'open' => intval($item['base']['open'] ?? '0')
                ],
            };

            $data[$type]['list'] = [];
            foreach ($item['list']??[] as $value) {
                $data[$type]['list'][] = [
                    'image' => UrlUtils::toRelativeUrl($value['image']??''),
                    'name'  => $value['name']??'',
                    'link'  => $value['link']??'',
                ];
            }
        }

        ConfigUtils::set('diy', 'person', $data);
    }
}