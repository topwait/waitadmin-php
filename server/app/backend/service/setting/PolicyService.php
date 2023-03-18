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
 * 政策协议服务类
 */
class PolicyService extends Service
{
    /**
     * 政策协议配置参数
     *
     * @return array
     * @author windy
     */
    #[ArrayShape(['service' => "string", 'privacy' => "string"])]
    public static function detail(): array
    {
        $detail = ConfigUtils::get('policy')??[];
        return [
            'service' => $detail['service']??'',
            'privacy' => $detail['privacy']??''
        ];
    }

    /**
     * 政策协议配置保存
     *
     * @param array $post
     * @author windy
     */
    public static function save(array $post): void
    {
        ConfigUtils::setItem('policy', [
            'service' => $post['service']??'',
            'privacy' => $post['privacy']??''
        ]);
    }
}