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

namespace app\frontend\service;

use app\common\basics\Service;
use app\common\service\wechat\WeChatConfig;
use app\common\service\wechat\WeChatService;
use EasyWeChat\Kernel\Exceptions\BadRequestException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use EasyWeChat\OfficialAccount\Application as OfficialApplication;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;
use think\facade\Log;
use Throwable;

class OfficialService extends Service
{

    /**
     * 微信公众号回调应答
     *
     * @return ResponseInterface
     * @throws InvalidArgumentException
     * @throws BadRequestException
     * @throws RuntimeException
     * @throws ReflectionException
     * @throws Throwable
     */
    public static function reply(): ResponseInterface
    {
        $config   = WeChatConfig::getOaConfig();
        $app      = new OfficialApplication($config);
        $oaServer = $app->getServer();

        $oaServer->addMessageListener('event', function ($message) {
            $eventKey = $message['EventKey'];
            $url = WeChatService::oaBuildAuthUrl('http://wa.waitshop.cn/frontend/login/oaLogin', $eventKey);
            return '<a href="'.$url.'">点击登录</a>';
        });

        return $oaServer->serve();
    }
}