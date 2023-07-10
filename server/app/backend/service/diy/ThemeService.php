<?php

namespace app\backend\service\diy;

use app\common\basics\Service;
use app\common\utils\ConfigUtils;

class ThemeService extends Service
{
    /**
     * 主题配置详情
     *
     * @return array
     * @author zero
     */
    public static function detail(): array
    {
        $config = ConfigUtils::get('diy', 'theme');
        return [
            'subject'         => $config['subject']         ?? '',
            'frontColor'      => $config['frontColor']      ?? '',
            'backgroundColor' => $config['backgroundColor'] ?? ''
        ] ?? [];
    }

    /**
     * 主题配置保存
     *
     * @param array $post
     * @author zero
     */
    public static function save(array $post): void
    {
        ConfigUtils::set('diy', 'theme', [
            'subject'         => $post['subject'],
            'frontColor'      => $post['frontColor'],
            'backgroundColor' => $post['backgroundColor']
        ]);
    }
}