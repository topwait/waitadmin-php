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

use app\common\utils\FileUtils;
use think\facade\Filesystem;

/**
 * 本地上传类
 *
 * Class Local
 * @package app\common\service\storage\engine
 */
class Local
{
    /**
     * 初始化
     *
     * Local constructor.
     * @param array $config
     */
    public function __construct(array $config=[])
    {
    }

    /**
     * 文件上传
     *
     * @author zero
     * @param array $fileInfo (文件信息)
     * @return void
     */
    public function upload(array $fileInfo): void
    {
        $arr  = explode('/', $fileInfo['fileName']);
        $name = array_pop($arr);
        array_shift($arr);
        $path = implode('/', $arr);

        $file = request()->file('file');
        $filesystem = Filesystem::instance();
        $filesystem->disk('public')->putFileAS($path, $file, $name);
    }

    /**
     * 本地上传
     *
     * @param string $path (地址)
     * @param string $key  (键名)
     */
    public function putFile(string $path, string $key): void
    {
        FileUtils::copy($path, $key);
    }

    /**
     * 远程上传
     *
     * @param string $url (地址)
     * @param string $key (键名)
     * @return void
     *@author zero
     */
    public function fetch(string $url, string $key=''): void
    {
        $content = file_get_contents($url);
        file_put_contents($key, $content);
    }

    /**
     * 文件删除
     *
     * @author zero
     * @param string $url
     * @return void
     */
    public function delete(string $url): void
    {
        $filePath = public_path() . $url;
        !file_exists($filePath) || unlink($filePath);
    }
}