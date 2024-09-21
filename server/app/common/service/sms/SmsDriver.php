<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/waitadmin-php
// | github:  https://github.com/topwait/waitadmin-php
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------

namespace app\common\service\sms;

use app\common\utils\ConfigUtils;
use Exception;

/**
 * 短信驱动类
 */
class SmsDriver
{
    /**
     * 短信配置参数
     */
    protected mixed $config;

    /**
     * 短信引擎
     */
    protected mixed $engine;

    /**
     * 构造方法
     *
     * SmsDriver constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $engine = ConfigUtils::get('sms', 'default', '');
        if (!$engine) {
            throw new Exception('尚未开启短信发送功能');
        }

        $this->config = ConfigUtils::get('sms', $engine, []);
        if (!$this->config) {
            throw new Exception('找不到短信相关配置');
        }

        $this->engine = $this->getEngineClass($engine);
    }

    /**
     * 发送短信
     *
     * @author zero
     * @param array $data
     * @return mixed
     */
    public function sendSms(array $data): mixed
    {
        return $this->engine
            ->setPhoneNumbers($data['mobile'])
            ->setTemplateCode($data['tplCode'])
            ->setTemplateParam($data['params'])
            ->send();
    }

    /**
     * 获取当前引擎驱动
     *
     * @param string $alias (驱动别名)
     * @return mixed
     * @throws Exception
     * @author zero
     */
    private function getEngineClass(string $alias): mixed
    {
        $engineName = $alias;
        $classSpace = __NAMESPACE__ . '\\engine\\' . ucfirst($engineName).'Sms';

        if (!class_exists($classSpace)) {
            throw new Exception('未找到存储引擎类: ' . $engineName);
        }

        return new $classSpace($this->config);
    }
}