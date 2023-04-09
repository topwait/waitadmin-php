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
use JetBrains\PhpStorm\ArrayShape;

/**
 * 存储配置服务类
 */
class StorageService extends Service
{
    /**
     * 存储配置详情
     *
     * @return array
     * @author zero
     */
    #[ArrayShape(['default' => "string", 'local' => "array", 'qiniu' => "string[]", 'aliyun' => "string[]", 'qcloud' => "string[]"])]
    public static function detail(): array
    {
        $config = ConfigUtils::get('storage');
        return [
            'default' => $config['default'] ?? 'local',
            'local'   => [],
            'qiniu'   => [
                'bucket'    => $config['qiniu']['bucket']    ?? '',
                'accessKey' => $config['qiniu']['accessKey'] ?? '',
                'secretKey' => $config['qiniu']['secretKey'] ?? '',
                'domain'    => $config['qiniu']['domain']    ?? ''
            ],
            'aliyun' => [
                'bucket'    => $config['aliyun']['bucket']    ?? '',
                'accessKey' => $config['aliyun']['accessKey'] ?? '',
                'secretKey' => $config['aliyun']['secretKey'] ?? '',
                'domain'    => $config['aliyun']['domain']    ?? ''
            ],
            'qcloud' => [
                'bucket'    => $config['qcloud']['bucket']    ?? '',
                'region'    => $config['qcloud']['region']    ?? '',
                'accessKey' => $config['qcloud']['accessKey'] ?? '',
                'secretKey' => $config['qcloud']['secretKey'] ?? '',
                'domain'    => $config['qcloud']['domain']    ?? ''
            ]
        ];
    }

    /**
     * 存储配置保存
     *
     * @param array $post
     * @author zero
     */
    public static function save(array $post): void
    {
        ConfigUtils::set('storage', 'default', $post['storage'] ?? 'local');
        ConfigUtils::set('storage', 'local', []);
        ConfigUtils::set('storage', 'qiniu', [
            'bucket'    => $post['qiniu_bucket'] ?? '',
            'accessKey' => $post['qiniu_ak'] ?? '',
            'secretKey' => $post['qiniu_sk'] ?? '',
            'domain'    => $post['qiniu_domain'] ?? '',
        ]);

        ConfigUtils::set('storage', 'aliyun', [
            'bucket'    => $post['aliyun_bucket'] ?? '',
            'accessKey' => $post['aliyun_ak'] ?? '',
            'secretKey' => $post['aliyun_sk'] ?? '',
            'domain'    => $post['aliyun_domain'] ?? '',
        ]);

        ConfigUtils::set('storage', 'qcloud', [
            'bucket'    => $post['qcloud_bucket'] ?? '',
            'region'    => $post['qcloud_region'] ?? '',
            'accessKey' => $post['qcloud_ak'] ?? '',
            'secretKey' => $post['qcloud_sk'] ?? '',
            'domain'    => $post['qcloud_domain'] ?? '',
        ]);
    }
}