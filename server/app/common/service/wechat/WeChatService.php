<?php

namespace app\common\service\wechat;


use EasyWeChat\MiniApp\Application;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
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
            $response = $api->get('sns/jscode2session', [
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
     * 小程序手机号码
     *
     * @document: https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/user-info/phone-number/getPhoneNumber.html
     * @param string $code (小程序生成的code)
     * @throws Exception
     */
    #[ArrayShape(['countryCode' => "int", 'phoneNumber' => "int"])]
    public static function wxPhoneNumber(string $code): array
    {
        try {
            $config = WeChatConfig::getWxConfig();
            $app = new Application($config);
            $api = $app->getClient();
            $response = $api->postJson('/wxa/business/getuserphonenumber', [
                'code' => $code
            ]);

            $result = json_decode($response, true);
            if ($result['errcode'] !== 0 || empty($result['phone_info'])) {
                $error = $result['errcode'].'：'.$result['errmsg'];
                throw new Exception($error);
            }

            return [
                'countryCode' => $result['phone_info']['countryCode'],
                'phoneNumber' => $result['phone_info']['phoneNumber']
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        } catch (TransportExceptionInterface $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 公众号链接生成
     *
     * @document: https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html
     * @param string $redirectUrl (重定向地址)
     * @param string $scopes (应用授权作用域: snsapi_base=(不弹授权只取openId) / snsapi_userinfo=(弹出授权,取用户信息))
     * @return string
     */
    public static function oaBuildAuthUrl(string $redirectUrl, string $scopes='snsapi_userinfo'): string
    {
        $config = WeChatConfig::getOaConfig();
        $redirectUri = urlencode($redirectUrl);
        return 'https://open.weixin.qq.com/connect/oauth2/authorize'
            .'?appid=' . $config['app_id']
            .'&redirect_uri=' . $redirectUri
            .'&response_type=code'
            .'&scope=' . $scopes
            .'&state=' . time()
            .'#wechat_redirect';
    }
}