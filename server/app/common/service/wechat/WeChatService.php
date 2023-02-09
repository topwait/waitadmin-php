<?php

namespace app\common\service\wechat;


use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\MiniApp\Application as MiniApplication;
use EasyWeChat\OfficialAccount\Application as OfficialApplication;
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
     * @return array ['openid', 'unionid', 'nickname', 'avatarUrl', 'sex']
     * @throws Exception
     * @author windy
     */
    #[ArrayShape(['openid' => "string", 'unionid' => "string", 'nickname' => "string", 'avatarUrl' => "string", 'sex' => "int"])]
    public static function oaAuth2session(string $code): array
    {
        try {
            $config = WeChatConfig::getOaConfig();
            $app    = new OfficialApplication($config);
            $oauth  = $app->getOauth();

            $response = $oauth
                ->scopes(['snsapi_userinfo'])
                ->userFromCode($code)
                ->getRaw();

            if (!isset($response['openid']) || empty($response['openid'])) {
                $error = $response['errcode'].'：'.$response['errmsg'];
                throw new Exception($error);
            }

            return [
                'openid'    => $response['openid']     ?? '',
                'unionid'   => $response['unionid']    ?? '',
                'nickname'  => $response['nickname']   ?? '',
                'avatarUrl' => $response['headimgurl'] ?? '',
                'sex'       => intval($response['sex'] ?? 0),
            ];
        } catch (InvalidArgumentException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 公众号链接生成
     *
     * @document: https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html
     * @param string $redirectUrl (重定向地址)
     * @return string url
     * @throws Exception
     */
    public static function oaBuildAuthUrl(string $redirectUrl): string
    {
        try {
            $config = WeChatConfig::getOaConfig();
            $app    = new OfficialApplication($config);
            $oauth  = $app->getOauth();

            $state = md5(time().rand(10000, 99999));

            return $oauth
                ->withState($state)
                ->scopes(['snsapi_userinfo'])
                ->redirect($redirectUrl);
        } catch (InvalidArgumentException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 开发平台登录凭证
     *
     * @document: https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html
     * @return array ['openid', 'unionid', 'access_token']
     * @throws Exception
     * @author windy
     */
    public static function opAuth2session(string $code): array
    {
        try {
            $config = WeChatConfig::getOpConfig();
            $app    = new OfficialApplication($config);
            $oauth  = $app->getOauth();

            $response = $oauth
                ->scopes(['snsapi_login'])
                ->userFromCode($code)
                ->getRaw();

            if (!isset($response['openid']) || empty($response['openid'])) {
                $error = $response['errcode'].'：'.$response['errmsg'];
                throw new Exception($error);
            }

            return $response;
        } catch (InvalidArgumentException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 开发平台链接生成
     *
     * @document: https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html
     * @param string $redirectUrl (重定向地址)
     * @return string ['url']
     * @throws Exception
     */
    public static function opBuildAuthUrl(string $redirectUrl): string
    {
        try {
            $config = WeChatConfig::getOpConfig();
            $app    = new OfficialApplication($config);
            $oauth  = $app->getOauth();

            return $oauth->scopes(['snsapi_login'])->redirect(urlencode($redirectUrl));
        } catch (InvalidArgumentException $e) {
            throw new Exception($e->getMessage());
        }
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
            $app = new MiniApplication($config);
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
     * @return array ['countryCode', 'phoneNumber']
     * @throws Exception
     */
    #[ArrayShape(['countryCode' => "int", 'phoneNumber' => "int"])]
    public static function wxPhoneNumber(string $code): array
    {
        try {
            $config = WeChatConfig::getWxConfig();
            $app = new MiniApplication($config);
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

}