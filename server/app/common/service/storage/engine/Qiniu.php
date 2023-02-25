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

use Exception;
use JetBrains\PhpStorm\Pure;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

/**
 * 七牛云OSS
 *
 * Class Qiniu
 * @package app\common\service\storage\engine
 */
class Qiniu
{
    private array $config; // 存储配置
    private Auth $auth;    // 存储鉴权

    /**
     * 初始化
     *
     * Qiniu constructor.
     * @param array $config
     */
    #[Pure]
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->auth = new Auth(
            $this->config['accessKey'],
            $this->config['secretKey']
        );
    }

    /**
     * 文件上传
     *
     * @param array $fileInfo
     * @throws Exception
     */
    public function upload(array $fileInfo): void
    {
        $upMgr = new UploadManager();
        $token = $this->auth->uploadToken($this->config['bucket']);
        list(, $err) = $upMgr->putFile(
            $token,
            $fileInfo['fileName'],
            $fileInfo['realPath']
        );

        if ($err !== null) {
            throw new Exception($err->message);
        }
    }

    /**
     * 本地上传
     *
     * @param string $path (根路径)
     * @param string $key  (键名)
     * @throws Exception
     */
    public function putFile(string $path, string $key)
    {
        $upMgr = new UploadManager();
        $token = $this->auth->uploadToken($this->config['bucket']);
        list(, $err) = $upMgr->putFile($token, $key, $path);

        if ($err !== null) {
            throw new Exception($err->message);
        }
    }

    /**
     * 远程上传
     *
     * @param string $url
     * @param string $key
     * @throws Exception
     * @author windy
     */
    public function fetch(string $url, string $key): void
    {
        $bcMgr = new BucketManager($this->auth);
        list(, $err) = $bcMgr->fetch($url, $this->config['bucket'], $key);
        if ($err !== null) {
            throw new Exception($err->message);
        }
    }

    /**
     * 文件删除
     *
     *
     * @param string $url (地址)
     * @throws Exception
     * @author windy
     */
    public function delete(string $url): void
    {
        $bcMgr = new BucketManager($this->auth);
        list(, $err) = $bcMgr->delete($this->config['bucket'], $url);
        if ($err !== null) {
            throw new Exception($err->message);
        }
    }
}