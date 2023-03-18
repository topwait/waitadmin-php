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

namespace app\common\service\storage\engine;

use Qcloud\Cos\Client;

/**
 * 腾讯云OSS
 *
 * Class Qcloud
 * @package app\common\service\storage\engine
 */
class Qcloud
{
    private array $config;      // 存储配置
    private Client $cosClient;  // 存储对象

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->cosClient = new Client([
            'region' => $this->config['region'],
            'credentials' => [
                'secretId'  => $this->config['accessKey'],
                'secretKey' => $this->config['secretKey'],
            ],
        ]);
    }

    /**
     * 文件上传
     *
     * @author windy
     * @param array $fileInfo (文件信息)
     * @author windy
     */
    public function upload(array $fileInfo): void
    {
        $this->cosClient->putObject([
            'Bucket' => $this->config['bucket'],
            'Key'    => $fileInfo['fileName'],
            'Body'   => fopen($fileInfo['realPath'], 'rb')
        ]);
    }

    /**
     * 本地上传
     *
     * @param string $path (地址)
     * @param string $key  (键名)
     * @author windy
     */
    public function putFile(string $path, string $key): void
    {
        $this->cosClient->putObject([
            'Bucket' => $this->config['bucket'],
            'Key'    => $key,
            'Body'   => fopen($path, 'rb')
        ]);
    }

    /**
     * 远程上传
     *
     * @param string $url (地址)
     * @param string $key (键名)
     * @author windy
     */
    public function fetch(string $url, string $key): void
    {
        $this->cosClient->putObject([
            'Bucket' => $this->config['bucket'],
            'Key'    => $key,
            'Body'   => fopen($url, 'rb')
        ]);
    }

    /**
     * 文件删除
     *
     * @param string $url (地址)
     * @author windy
     */
    public function delete(string $url): void
    {
        $this->cosClient->deleteObject(array(
            'Bucket' => $this->config['bucket'],
            'Key'    => $url
        ));
    }
}