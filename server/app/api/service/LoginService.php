<?php

namespace app\api\service;

use app\api\widgets\UserWidget;
use app\common\basics\Service;
use app\common\model\user\User;
use app\common\model\user\UserAuth;
use app\common\service\wechat\WeChatService;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

class LoginService extends Service
{
    /**
     * 微信登录
     *
     * @param string $code  (微信小程序编码)
     * @param int $terminal (客户端[1=微信小程序, 2=微信公众号, 3=H5, 4=PC, 5=安卓, 6=苹果])
     * @return array
     * @throws Exception
     */
    #[ArrayShape(['token' => "int"])]
    public static function wxLogin(string $code, int $terminal): array
    {
        $response = WeChatService::wxJsCode2session($code);
        $response['terminal'] = $terminal;

        $userInfo = UserWidget::getUserAuthByResponse($response);
        if (empty($userInfo)) {
            $userId = UserWidget::createUser($response);
        } else {
            $userId = UserWidget::updateUser($response);
        }

        return ['token'=>$userId];
    }

}