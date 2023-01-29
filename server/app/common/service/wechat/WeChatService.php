<?php

namespace app\common\service\wechat;


use EasyWeChat\MiniApp\Application;
use Exception;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * 微信功能服务类
 */
class WeChatService
{
    /**
     * 公众号登录凭证
     *
     * @document: https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html
     * @author windy
     */
    public static function oaAuth2session(string $code)
    {

    }

    /**
     * 小程序登录凭证
     *
     * @document: https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/user-login/code2Session.html
     * @param string $code (小程序生成的code)
     * @return array ['session_key', 'openid']
     * @throws Exception
     * @author windy
     */
    public static function wxJsCode2session(string $code): array
    {
        try {
            $config = WeChatConfig::getWxConfig();
            $app = new Application($config);
            $api = $app->getClient();
            $response = $api->get('sns/js'.'code2session', [
                'appid'      => $config['app_id'],
                "secret"     => $config['secret'],
                "js_code"    => $code,
                "grant_type" => 'authorization_code',
            ]);

            $result = json_decode($response, true);
            if (!isset($result['openid']) || empty($result['openid'])) {
                $error = $result['errcode'].'：'.$result['errmsg'];
                throw new Exception($error);
            }

            return (array) $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        } catch (TransportExceptionInterface $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 公众号链接生成
     */
    public static function oaBuildAuthUrl()
    {

    }
}