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

namespace app\common\service\storage;


use app\backend\service\setting\WatermarkService;
use app\common\utils\UrlUtils;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use think\Image;

/**
 * 存储驱动类
 *
 * Class StorageDriver
 * @package app\common\service\storage
 */
class StorageDriver
{
    private array $config; // 存储配置
    private mixed $engine; // 存储引擎

    /**
     * 初始化
     *
     * Driver constructor.
     * @param array $config
     * @param string|null $storage
     * @throws Exception
     */
    public function __construct(array $config, string $storage = null)
    {
        $this->config = $config;
        $this->engine = $this->getEngineClass($storage);
    }

    /**
     * 上传文件
     *
     * @param string $type (类型: image/video/package/document)
     * @param string $dir  (目录: attach)
     * @return array
     * @throws Exception
     * @author windy
     */
    #[ArrayShape(['type' => "string", 'ext' => "mixed", 'size' => "mixed", 'mime' => "mixed", 'name' => "mixed", 'realPath' => "mixed", 'fileName' => "string"])]
    public function upload(string $type, string $dir='attach'): array
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

        $dir   = ($dir && str_ends_with($dir, '/')) ? $dir : $dir.'/';
        $dir   = ($dir && str_starts_with($dir, '/')) ? $dir : '/'.$dir;
        $disks = trim(config('filesystem.disks.public.url'), '/');
        $disks = $disks . $dir. $type . '/';

        $fileInfo = [
            'type'     => $type,
            'ext'      => $extension,
            'size'     => $file->getSize(),
            'mime'     => $file->getMime(),
            'name'     => $file->getOriginalName(),
            'realPath' => $file->getRealPath(),
            'fileName' => $disks . $this->buildSaveName($file->getRealPath(), $extension)
        ];

        $this->watermark($fileInfo);

        $this->engine->upload($fileInfo);

        return $fileInfo;
    }

    /**
     * 上传验证
     *
     * @author windy
     * @param string $type (类型: image/video/package/document)
     */
    public function validates(string $type): void
    {
        $limit = match ($type) {
            'image'    => config('project.uploader.image')    ?? ['size' => 10485760, 'ext' => ['png', 'jpg', 'jpeg', 'gif', 'ico', 'bmp']],
            'video'    => config('project.uploader.video')    ?? ['size' => 31457280, 'ext' => ['mp4', 'mp3', 'avi', 'flv', 'rmvb', 'mov']],
            'package'  => config('project.uploader.package')  ?? ['size' => 31457280, 'ext' => ['zip', 'rar', 'iso', '7z', 'tar', 'gz', 'arj', 'bz2']],
            'document' => config('project.uploader.document') ?? ['size' => 31457280, 'ext' => ['txt', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'pem']],
            default => ['size' => 4194304, 'ext' => ['png', 'jpg', 'jpeg', 'gif', 'ico', 'mp4', 'mov', 'avi', 'flv']],
        };

        $fileExt = implode(',', $limit['ext']);
        validate(['file' => 'fileSize:'.$limit['size'] . '|fileExt:'.$fileExt])->check(request()->file());
    }

    /**
     * 生成文件名
     *
     * @author windy
     * @param string $realPath (临时路径)
     * @param string $ext      (文件后缀)
     * @return string          (日期名称)
     */
    public function buildSaveName(string $realPath, string $ext): string
    {
        return date('Ymd') . '/'
            . date('His')
            . substr(md5($realPath), 0, 8)
            . substr(md5(microtime()), 5, 10)
            . str_pad(strval(rand(0, 9999)), 5, '0', STR_PAD_LEFT)
            . ".$ext";
    }

    /**
     * 图片水印
     *
     * @param array $fileInfo
     * @author windy
     */
    private function watermark(array $fileInfo): void
    {
        if ($fileInfo['type'] === 'image') {
            $water = request()->post('water', 'true');
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
                    $watermark['ttf'] = public_path() . 'static/common/watermark.ttf';
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
     * @author windy
     * @param string|null $storage (引擎名称)
     * @return mixed
     * @throws Exception
     */
    private function getEngineClass(string $storage=null): mixed
    {
        $engineName = is_null($storage) ? $this->config['engine'] : $storage;
        $classSpace = __NAMESPACE__ . '\\engine\\' . ucfirst($engineName);

        if (!class_exists($classSpace)) {
            throw new Exception('未找到存储引擎类: ' . $engineName);
        }

        return new $classSpace($this->config['params']);
    }
}