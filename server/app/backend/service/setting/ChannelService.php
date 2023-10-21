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
 * 渠道配置服务类
 */
class ChannelService extends Service
{
    /**
     * 渠道配置信息
     *
     * @return array[]
     * @author zero
     */
    public static function detail(): array
    {
        $wxChannel = ConfigUtils::get('wx_channel');
        $oaChannel = ConfigUtils::get('oa_channel');
        $opChannel = ConfigUtils::get('op_channel');
        $domain = $_SERVER['SERVER_NAME'];

        return [
                'wx' => [
                    'name'                  => $wxChannel['name']        ?? '',
                    'app_id'                => $wxChannel['app_id']      ?? '',
                    'app_secret'            => $wxChannel['app_secret']  ?? '',
                    'original_id'           => $wxChannel['original_id'] ?? '',
                    'qr_code'               => UrlUtils::toAbsoluteUrl($wxChannel['qr_code'] ?? ''),
                    'request_domain'        => 'https://'.$domain,
                    'socket_domain'         => 'wss://'.$domain,
                    'upload_file_domain'    => 'https://'.$domain,
                    'download_file_domain'  => 'https://'.$domain,
                    'udp_domain'            => 'udp://'.$domain,
                    'tcp_domain'            => 'tcp://'.$domain,
                    'business_domain'       => $domain,
                ],
                'oa' => [
                    'name'                  => $oaChannel['name']        ?? '',
                    'app_id'                => $oaChannel['app_id']      ?? '',
                    'app_secret'            => $oaChannel['app_secret']  ?? '',
                    'original_id'           => $oaChannel['original_id'] ?? '',
                    'token'                 => $oaChannel['token']       ?? '',
                    'aes_key'               => $oaChannel['aes_key']     ?? '',
                    'encryption_type'       => $oaChannel['encryption_type'] ?? 1,
                    'qr_code'               => UrlUtils::toAbsoluteUrl($oaChannel['qr_code'] ?? ''),
                    'domain'                => $domain,
                    'url'                   => url('api/official/reply', [],'',true).'',
                ],
                'op' => [
                    'app_id'                => $opChannel['app_id']     ?? '',
                    'app_secret'            => $opChannel['app_secret'] ?? '',
                ]
            ] ?? [];
    }

    /**
     * 渠道配置保存
     *
     * @param array $post
     * @author zero
     */
    public static function save(array $post): void
    {
        ConfigUtils::setItem('wx_channel', [
            'name'        => trim($post['wx_name']??''),
            'app_id'      => trim($post['wx_app_id']??''),
            'app_secret'  => trim($post['wx_app_secret']??''),
            'original_id' => trim($post['wx_original_id']??''),
            'qr_code'     => UrlUtils::toRelativeUrl($post['wx_qr_code']??'')
        ]);

        ConfigUtils::setItem('oa_channel', [
            'name'            => trim($post['oa_name']??''),
            'app_id'          => trim($post['oa_app_id']??''),
            'app_secret'      => trim($post['oa_app_secret']??''),
            'token'           => trim($post['oa_token']??''),
            'aes_key'         => trim($post['oa_aes_key']??''),
            'encryption_type' => intval($post['oa_encryption_type']??1),
            'original_id'     => trim($post['oa_original_id']??''),
            'qr_code'         => UrlUtils::toRelativeUrl($post['oa_qr_code']??'')
        ]);

        ConfigUtils::setItem('op_channel', [
            'app_id'     => trim($post['op_app_id']??''),
            'app_secret' => trim($post['op_app_secret']??'')
        ]);
    }
}