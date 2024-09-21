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

namespace app\common\service\storage;

use app\backend\service\setting\WatermarkService;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;
use Exception;
use think\Image;

/**
 * 存储驱动类
 */
class StorageDriver
{
    private array $config; // 存储配置
    private mixed $engine; // 存储引擎

    /**
     * 初始化
     *
     * Driver constructor.
     * @param array $config (配置参数)
     * @param string|null $engine (存储引擎)
     * @throws Exception
     */
    public function __construct(array $config = [], string $engine = null)
    {
        if (!$config && !$engine) {
            $engine = ConfigUtils::get('storage', 'default', 'local');
            $params = ConfigUtils::get('storage', $engine, []);
            $config = array_merge($params, $config);
        } elseif ($engine) {
            $params = ConfigUtils::get('storage', $engine, []);
            $config = array_merge($params, $config);
        }

        $this->config = $config;
        $this->engine = $this->getEngineClass($engine);
    }

    /**
     * 上传验证
     *
     * @param string $type (类型: picture/video/document/package)
     * @author zero
     */
    public function validates(string $type): void
    {
        $limit = match ($type) {
            'picture'  => config('project.uploader.image')    ?? ['size' => 10485760, 'ext' => ['png', 'jpg', 'jpeg', 'gif', 'ico', 'bmp']],
            'video'    => config('project.uploader.video')    ?? ['size' => 31457280, 'ext' => ['mp4', 'mp3', 'avi', 'flv', 'rmvb', 'mov']],
            'package'  => config('project.uploader.package')  ?? ['size' => 31457280, 'ext' => ['zip', 'rar', 'iso', '7z', 'tar', 'gz', 'arj', 'bz2']],
            'document' => config('project.uploader.document') ?? ['size' => 31457280, 'ext' => ['txt', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'pem']],
            default => ['size' => 4194304, 'ext' => ['png', 'jpg', 'jpeg', 'gif', 'ico', 'mp4', 'mov', 'avi', 'flv']],
        };

        $fileExt = implode(',', $limit['ext']);
        validate(['file' => 'fileSize:'.$limit['size'] . '|fileExt:'.$fileExt])->check(request()->file());
    }

    /**
     * 上传文件
     *
     * @param string $type (类型: picture/video/document/package)
     * @return array
     * @throws Exception
     * @author zero
     */
    public function upload(string $type): array
    {
        $file = request()->file('file');
        if (!$file) {
            throw new Exception('未找到上传文件的信息');
        }

        $this->validates($type);

        $extension = $file->extension();
        if (!$file->extension()) {
            $extension = explode('/', $file->getMime())[1];
        }

        $disks = trim(config('filesystem.disks.public.url'), '/');
        $disks = $disks . '/' . $type . '/';

        $detail['info'] = [
            'type'     => $type,
            'ext'      => $extension,
            'size'     => $file->getSize(),
            'mime'     => $file->getMime(),
            'name'     => $file->getOriginalName(),
            'realPath' => $file->getRealPath(),
            'fileName' => $disks . $this->buildSaveName($file->getRealPath(), $extension)
        ];

        $this->watermark($detail['info']);

        $this->engine->upload($detail['info']);

        return $detail['info'];
    }

    /**
     * 本地上传
     *
     * @param string $url (路径)
     * @param string $key (键值)
     * @author zero
     */
    public function putFile(string $url, string $key): void
    {
        $this->engine->putFile($url, $key);
    }

    /**
     * 远程上传
     *
     * @param string $url (地址)
     * @param string $key (键值)
     * @author zero
     */
    public function fetch(string $url, string $key): void
    {
        $this->engine->fetch($url, $key);
    }

    /**
     * 文件删除
     *
     * @param string $url (地址)
     * @author zero
     */
    public function delete(string $url): void
    {
        $this->engine->delete($url);
    }

    /**
     * 生成文件名
     *
     * @param string $realPath (临时路径)
     * @param string $ext      (文件后缀)
     * @return string          (日期名称)
     * @author zero
     */
    public function buildSaveName(string $realPath, string $ext): string
    {
        return date('Ymd') . '/'
            . date('His')
            . substr(md5($realPath), 0, 9)
            . substr(md5(microtime()), 5, 12)
            . str_pad(strval(rand(0, 9999)), 5, '0', STR_PAD_LEFT)
            . ".$ext";
    }

    /**
     * 图片水印
     *
     * @param array $fileInfo
     * @author zero
     */
    private function watermark(array $fileInfo): void
    {
        if ($fileInfo['type'] === 'picture') {
            $water = request()->post('water', 'false');
            $watermark = WatermarkService::detail();
            if ($watermark['status'] && $water === 'true') {
                $image = Image::open($fileInfo['realPath']);
                if ($watermark['type'] == 'image') {
                    $watermark['icon'] = public_path() . UrlUtils::toRelativeUrl($watermark['icon']);
                    $image->water(
                        $watermark['icon'],
                        $watermark['position'],
                        $watermark['alpha']
                    )->save($fileInfo['realPath']);
                } else {
                    $watermark['ttf'] = public_path() . 'static/common/images/watermark.ttf';
                    $image->text(
                        $watermark['fonts'],
                        $watermark['ttf'],
                        $watermark['size'],
                        $watermark['color'],
                        $watermark['position'],
                        $watermark['offset'],
                        $watermark['angle']
                    )->save($fileInfo['realPath']);
                }
            }
        }
    }

    /**
     * 取存储引擎
     *
     * @param string|null $engine (引擎名称)
     * @return mixed
     * @throws Exception
     * @author zero
     */
    private function getEngineClass(string $engine=null): mixed
    {
        $classSpace = __NAMESPACE__ . '\\engine\\' . ucfirst($engine) . 'Oss';
        if (!class_exists($classSpace)) {
            throw new Exception('未找到存储引擎类: ' . $engine);
        }

        return new $classSpace($this->config);
    }
}