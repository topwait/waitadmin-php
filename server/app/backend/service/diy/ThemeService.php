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
            'type'  => $config['type'] ?? '',
            'color' => $config['color'] ?? ''
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
            'type'  => $post['type'],
            'color' => $post['color']
        ]);
    }
}