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

namespace app\backend\service;

use app\common\basics\Service;
use app\common\model\user\UserAuth;
use app\common\service\wechat\WeChatConfig;
use app\common\service\wechat\WeChatService;
use app\frontend\cache\ScanLoginCache;
use EasyWeChat\Kernel\Exceptions\BadRequestException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use EasyWeChat\OfficialAccount\Application as OfficialApplication;
use Exception;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;
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
            $openId = $message['FromUserName'];
            $eventArr = explode(':', $message['EventKey']);
            return match ($eventArr[0]) {
                'login' => self::pcWxLogin($eventArr[1], $openId),
                'bind'  => self::pcWxBind($eventArr[1]),
                default => 'Welcome'
            };
        });

        return $oaServer->serve();
    }

    /**
     * PC端微信登录
     *
     * @param string $openId
     * @param string $state
     * @return string
     * @throws Exception
     * @author zero
     */
    private static function pcWxLogin(string $state, string $openId): string
    {
        $auth = (new UserAuth())->where(['openid'=>$openId])->findOrEmpty();
        if ($auth->isEmpty()) {
            $redirectUrl = request()->domain() . '/frontend/login/oaLogin';
            $oaBuildUrl  = WeChatService::oaBuildAuthUrl($redirectUrl, $state);
            return '<a href="'. $oaBuildUrl .'">点击登录</a>';
        } else {
            ScanLoginCache::set($state, [
                'status' => ScanLoginCache::$OK,
                'userId' => $auth['user_id']
            ]);
            return '登录成功';
        }
    }

    /**
     * PC端微信绑定
     *
     * @param string $state
     * @return string
     * @throws Exception
     * @author zero
     */
    private static function pcWxBind(string $state): string
    {
        $redirectUrl = request()->domain() . '/frontend/user/bindWeChat';
        $oaBuildUrl  = WeChatService::oaBuildAuthUrl($redirectUrl, $state);
        return '<a href="'. $oaBuildUrl .'">点击绑定</a>';
    }
}