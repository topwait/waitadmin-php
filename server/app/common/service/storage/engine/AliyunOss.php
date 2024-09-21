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
declare (strict_types = 1);

namespace app\common\service\storage\engine;

use OSS\Core\OssException;
use OSS\Http\RequestCore_Exception;
use OSS\OssClient;

/**
 * 阿里云OSS
 */
class AliyunOss
{
    private array $config;         // 存储配置
    private OssClient $ossClient;  // 存储对象

    /**
     * 初始化
     *
     * Local constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config    = $config;
        $this->ossClient = new OssClient(
            $this->config['accessKey'],
            $this->config['secretKey'],
            $this->config['domain'],
            true
        );

    }

    /**
     * 文件上传
     *
     * @param array $fileInfo (文件信息)
     * @throws OssException|RequestCore_Exception
     * @author zero
     */
    public function upload(array $fileInfo): void
    {
        $this->ossClient->uploadFile(
            $this->config['bucket'],
            $fileInfo['fileName'],
            $fileInfo['realPath']
        );
    }

    /**
     * 本地上传
     *
     * @param string $path
     * @param string $key
     * @throws OssException|RequestCore_Exception
     */
    public function putFile(string $path, string $key): void
    {
        $this->ossClient->uploadFile($this->config['bucket'], $key, $path);
    }

    /**
     * 远程上传
     *
     * @param string $url
     * @param string $key
     * @throws OssException
     * @throws RequestCore_Exception
     * @author zero
     */
    public function fetch(string $url, string $key): void
    {
        $content = file_get_contents($url);
        $this->ossClient->putObject(
            $this->config['bucket'],
            $key,
            $content
        );
    }

    /**
     * 文件删除
     *
     * @param string $url
     * @throws OssException
     * @throws RequestCore_Exception
     * @author zero
     */
    public function delete(string $url): void
    {
        $this->ossClient->deleteObject($this->config['bucket'], $url);
    }
}