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

namespace app\common\service\sms\engine;


use Exception;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Sms\V20190711\Models\SendSmsRequest;
use TencentCloud\Sms\V20190711\SmsClient;

/**
 * 腾讯短信驱动
 */
class TencentSms
{
    /**
     * 配置参数
     */
    private array $config;

    /**
     * 要发送的手机号
     */
    private mixed $phoneNumbers;

    /**
     * 模板编码
     */
    private string $templateCode;

    /**
     * 短信参数
     */
    private string $templateParam;

    /**
     * 初始化配置
     *
     * AliyunSms constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * 发送腾讯云短信
     *
     * @throws Exception
     * @author windy
     */
    public function send(): bool
    {
        try {
            $cred = new Credential($this->config['secret_id'], $this->config['secret_key']);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint('sms.tencentcloudapi.com');
            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new SmsClient($cred, "", $clientProfile);
            $req = new SendSmsRequest();

            $params = [
                'Sign'             => $this->config['sign'],
                'SmsSdkAppid'      => $this->config['app_id'],
                'TemplateID'       => $this->templateCode,
                'PhoneNumberSet'   => ['+86' . $this->phoneNumbers],
                'TemplateParamSet' => $this->templateParam,
            ];

            $req->fromJsonString(json_encode($params));
            $resp = json_decode($client->SendSms($req)->toJsonString(), true);
            if (isset($resp['SendStatusSet']) && $resp['SendStatusSet'][0]['Code'] == 'Ok') {
                return true;
            } else {
                $message = $res['SendStatusSet'][0]['Message'] ?? json_encode($resp);
                throw new Exception($message);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 设置发送手机号码
     *
     * @param int $mobile
     * @return $this
     * @author windy
     */
    public function setPhoneNumbers(int $mobile): TencentSms
    {
        $this->phoneNumbers = trim($mobile);
        return $this;
    }

    /**
     * 设置短信模板编码
     *
     * @param string $code
     * @return $this
     * @author windy
     */
    public function setTemplateCode(string $code): TencentSms
    {
        $this->templateCode = trim($code);
        return $this;
    }

    /**
     * 设置短信发送参数
     *
     * @param array $param
     * @return $this
     * @author windy
     */
    public function setTemplateParam(array $param): TencentSms
    {
        $this->templateParam = json_encode($param, JSON_UNESCAPED_UNICODE);
        return $this;
    }
}