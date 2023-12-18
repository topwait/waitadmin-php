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

namespace app\common\service\wechat;

use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\MiniApp\Application as MiniApplication;
use EasyWeChat\OfficialAccount\Application as OfficialApplication;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
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
     * @return array ['openid', 'unionid', 'nickname', 'avatarUrl', 'gender']
     * @throws Exception
     * @author zero
     */
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
                'gender'    => intval($response['sex'] ?? 0),
            ] ?? [];
        } catch (InvalidArgumentException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 公众号链接生成
     *
     * @document: https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html
     * @param string $redirectUrl (重定向地址)
     * @param string $state (状态码,用于标记是否超时)
     * @return string url
     * @throws Exception
     * @author zero
     */
    public static function oaBuildAuthUrl(string $redirectUrl, string $state): string
    {
        try {
            $config = WeChatConfig::getOaConfig();
            $app    = new OfficialApplication($config);
            $oauth  = $app->getOauth();

            return $oauth
                ->withState($state)
                ->scopes(['snsapi_userinfo'])
                ->redirect($redirectUrl);
        } catch (InvalidArgumentException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 公众号扫码链接
     *
     * @param string $ticketCode
     * @return array
     * @throws Exception
     * @author zero
     */
    public static function oaQrCodeUrl(string $ticketCode): array
    {
        try {
            $config = WeChatConfig::getOaConfig();
            $app    = new OfficialApplication($config);
            $client = $app->getClient();
            $accessToken = $app->getAccessToken()->getToken();

            // 获取二维码
            $createQrcode = $client->post('/cgi-bin/qrcode/create?access_token='.$accessToken, [
                'body' => json_encode([
                    'expire_seconds' => 120,
                    'action_name'    => 'QR_STR_SCENE',
                    'action_info'    => ['scene' => ['scene_str' => $ticketCode]],
                ])
            ])->getContent();

            // 二维码内容
            $createQrcode = json_decode($createQrcode, true);
            if (!isset($createQrcode['ticket'])) {
                // access_token失效
                if (isset($createQrcode['errcode']) == 40001) {
                    $accessToken = $accessToken->refresh();
                    $createQrcode = $client->post('/cgi-bin/qrcode/create?access_token='.$accessToken, [
                        'body' => json_encode([
                            'expire_seconds' => 120,
                            'action_name'    => 'QR_STR_SCENE',
                            'action_info'    => ['scene' => ['scene_str' => $ticketCode]],
                        ])
                    ])->getContent();
                }

                if (!isset($createQrcode['ticket'])) {
                    throw new Exception($createQrcode['errmsg']);
                }
            } else {
                throw new Exception($createQrcode['errmsg']);
            }

            return [
                'url'    => 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$createQrcode['ticket'],
                'key'    => $ticketCode,
                'ticket' => $createQrcode['ticket'],
                'expire_seconds' => $createQrcode['expire_seconds']
            ]??[];
        } catch (Exception|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            throw new Exception($e->getMessage());
        }
    }

//    /**
//     * PC开发平台登录凭证
//     *
//     * @document: https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html
//     * @return array ['openid', 'unionid', 'access_token']
//     * @throws Exception
//     * @author zero
//     */
//    public static function opAuth2session(string $code): array
//    {
//        try {
//            $config = WeChatConfig::getOaConfig();
//            $app    = new OfficialApplication($config);
//            $oauth  = $app->getOauth();
//
//            $response = $oauth
//                ->scopes(['snsapi_login'])
//                ->userFromCode($code)
//                ->getRaw();
//
//            if (!isset($response['openid']) || empty($response['openid'])) {
//                $error = $response['errcode'].'：'.$response['errmsg'];
//                throw new Exception($error);
//            }
//
//            return [
//                'openid'    => $response['openid']     ?? '',
//                'unionid'   => $response['unionid']    ?? '',
//                'nickname'  => $response['nickname']   ?? '',
//                'avatarUrl' => $response['headimgurl'] ?? "",
//                'gender'    => intval($response['sex'] ?? 0),
//            ] ?? [];
//        } catch (InvalidArgumentException $e) {
//            throw new Exception($e->getMessage());
//        }
//    }

//    /**
//     * PC开发平台链接生成
//     *
//     * @document: https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html
//     * @param string $redirectUrl (重定向地址)
//     * @param string $state (状态码,用于标记是否超时)
//     * @return string url
//     * @throws Exception
//     */
//    public static function opBuildAuthUrl(string $redirectUrl, string $state): string
//    {
//        try {
//            $config = WeChatConfig::getOpConfig();
//            $app    = new OfficialApplication($config);
//            $oauth  = $app->getOauth();
//
//            return $oauth->scopes(['snsapi_login'])->withState($state)->redirect($redirectUrl);
//        } catch (InvalidArgumentException $e) {
//            throw new Exception($e->getMessage());
//        }
//    }

    /**
     * 小程序登录凭证
     *
     * @document: https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/user-login/code2Session.html
     * @param string $code (小程序生成的code)
     * @return array ['session_key', 'openid']
     * @throws Exception
     * @author zero
     */
    public static function wxJsCode2session(string $code): array
    {
        try {
            $config = WeChatConfig::getWxConfig();
            $app = new MiniApplication($config);
            $api = $app->getClient();
            $result = $api->get('sns/jscode2session', [
                'appid'      => $config['app_id'],
                "secret"     => $config['secret'],
                "js_code"    => $code,
                "grant_type" => 'authorization_code',
            ]);

            $response = json_decode(strval($result), true);
            if (!isset($response['openid']) || empty($response['openid'])) {
                $error = $response['errcode'].'：'.$response['errmsg'];
                throw new Exception($error);
            }

            return [
                'session_key' => $response['session_key'] ?? '',
                'openid'      => $response['openid'] ?? '',
                'unionid'     => $response['unionid'] ?? '',
            ] ?? [];
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
    public static function wxPhoneNumber(string $code): array
    {
        try {
            $config = WeChatConfig::getWxConfig();
            $app = new MiniApplication($config);
            $api = $app->getClient();
            $response = $api->postJson('/wxa/business/getuserphonenumber', [
                'code' => $code
            ]);

            $result = json_decode(strval($response), true);
            if ($result['errcode'] !== 0 || empty($result['phone_info'])) {
                $error = $result['errcode'].'：'.$result['errmsg'];
                throw new Exception($error);
            }

            return [
                'countryCode' => $result['phone_info']['countryCode'],
                'phoneNumber' => $result['phone_info']['phoneNumber']
            ] ?? [];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        } catch (TransportExceptionInterface $e) {
            throw new Exception($e->getMessage());
        }
    }
}