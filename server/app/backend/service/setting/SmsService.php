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

namespace app\backend\service\setting;

use app\common\basics\Service;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;

/**
 * 短信配置服务类
 */
class SmsService extends Service
{
    /**
     * 短信引擎列表
     *
     * @return array[]
     * @author zero
     */
    public static function lists(): array
    {
        $config = ConfigUtils::get('sms');

        return [
            [
                'alias'  => 'aliyun',
                'name'   => '阿里云短信',
                'desc'   => '阿里云短信服务（Short Message Service）',
                'enable' => ($config['default']??'') === 'aliyun' ? 1 : 0,
                'image'  => UrlUtils::toAbsoluteUrl('/static/backend/images/service/aliyun.png')
            ],
            [
                'alias'  => 'tencent',
                'name'   => '阿里云短信',
                'desc'   => '阿里云短信服务（Short Message Service）',
                'enable' => ($config['default']??'') === 'tencent' ? 1 : 0,
                'image'  => UrlUtils::toAbsoluteUrl('/static/backend/images/service/tencent.png')
            ]
        ];
    }

    /**
     * 短信引擎详情
     *
     * @param string $alias (引擎名称)
     * @return array
     * @author zero
     */
    public static function detail(string $alias): array
    {
        $detail = [];
        $engine = ConfigUtils::get('sms', 'default', '');

        switch ($alias) {
            case 'aliyun':
                $config = ConfigUtils::get('sms', 'aliyun');
                $detail['res'] = [
                    'alias'  => 'aliyun',
                    'name'   => '阿里云短信',
                    'enable' => $engine === 'aliyun' ? 1 : 0,
                    'params' => [
                        'sign'          => $config['sign']??'',
                        'access_key_id' => $config['access_key_id']??'',
                        'access_secret' => $config['access_secret']??'',
                    ]
                ];
                break;
            case 'tencent':
                $config = ConfigUtils::get('sms', 'tencent');
                $detail['res'] = [
                    'alias'  => 'tencent',
                    'name'   => '腾讯云短信',
                    'enable' => $engine === 'tencent' ? 1 : 0,
                    'params' => [
                        'sign'       => $config['sign']??'',
                        'app_id'     => $config['app_id']??'',
                        'secret_id'  => $config['secret_id']??'',
                        'secret_key' => $config['secret_key']??'',
                    ]
                ];
        }

        return (array) $detail['res'];
    }

    /**
     * 短信引擎配置
     *
     * @param array $post (参数)
     * @author zero
     */
    public static function save(array $post): void
    {
        $alias = strtolower($post['alias']);
        $engine = ConfigUtils::get('sms', 'default', '');

        $param = match ($alias) {
            'aliyun' => json_encode([
                'sign'          => trim($post['sign'] ?? ''),
                'access_key_id' => trim($post['access_key_id'] ?? ''),
                'access_secret' => trim($post['access_secret'] ?? ''),
            ]),
            'tencent' => json_encode([
                'sign'       => trim($post['sign'] ?? ''),
                'app_id'     => trim($post['app_id'] ?? ''),
                'secret_id'  => trim($post['secret_id'] ?? ''),
                'secret_key' => trim($post['secret_key'] ?? ''),
            ], JSON_UNESCAPED_UNICODE),
        };

        ConfigUtils::set('sms', $alias, $param);
        if ($engine === $alias && !($post['enable']??0)) {
            ConfigUtils::set('sms', 'default', '');
        } else if (($post['enable']??0)) {
            ConfigUtils::set('sms', 'default', $alias);
        }
    }
}