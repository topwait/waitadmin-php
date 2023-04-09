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


use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use Exception;

/**
 * 阿里云短信驱动
 */
class AliyunSms
{
    /**
     * 配置参数
     */
    private array $config;

    /**
     * 要发送的手机号
     */
    private int $phoneNumbers;

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
     * 发送阿里云短信
     *
     * @return bool
     * @throws Exception
     * @author zero
     */
    public function send(): bool
    {
        try {
            AlibabaCloud::accessKeyClient($this->config['access_key_id'], $this->config['access_secret'])
                ->regionId('cn-hangzhou')
                ->asDefaultClient();

            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'SignName'      => $this->config['sign'],
                        'PhoneNumbers'  => $this->phoneNumbers,
                        'TemplateCode'  => $this->templateCode,
                        'TemplateParam' => $this->templateParam,
                    ],
                ])->request();

            $result = $result->toArray();
            if (isset($result['Code']) && $result['Code'] == 'OK') {
                return true;
            }

            throw new Exception($result['Message'] ?? $result);
        } catch (ClientException | ServerException $e) {
            throw new Exception($e->getErrorMessage());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 设置发送手机号码
     *
     * @param int $mobile
     * @return $this
     * @author zero
     */
    public function setPhoneNumbers(int $mobile): AliyunSms
    {
        $this->phoneNumbers = trim($mobile);
        return $this;
    }

    /**
     * 设置短信模板编码
     *
     * @param string $code
     * @return $this
     * @author zero
     */
    public function setTemplateCode(string $code): AliyunSms
    {
        $this->templateCode = trim($code);
        return $this;
    }

    /**
     * 设置短信发送参数
     *
     * @param array $param
     * @return $this
     * @author zero
     */
    public function setTemplateParam(array $param): AliyunSms
    {
        $this->templateParam = json_encode($param, JSON_UNESCAPED_UNICODE);
        return $this;
    }
}