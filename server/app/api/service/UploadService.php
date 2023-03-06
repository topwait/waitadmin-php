<?php

namespace app\api\service;

use app\common\basics\Service;
use app\common\exception\UploadException;
use app\common\service\storage\StorageDriver;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use think\facade\Filesystem;

class UploadService extends Service
{
    /**
     * 上传
     *
     * @param string $type
     * @param string $dir
     * @return array
     * @throws UploadException
     * @author windy
     */
    #[ArrayShape(['name' => "string", 'ext' => "string", 'size' => "int", 'url' => "string"])]
    public static function storage(string $type, string $dir): array
    {
        try {
            // 存储引擎
            $engine = ConfigUtils::get('storage', 'default', 'local');
            $params = ConfigUtils::get('storage', $engine, []);

            // 上传调用
            $storageDriver = new StorageDriver(['engine'=>$engine, 'params'=>$params]);
            $fileInfo = $storageDriver->upload($type, $dir);

            // 返回信息
            return [
                'name' => $fileInfo['name'],
                'ext'  => $fileInfo['ext'],
                'size' => $fileInfo['size'],
                'url'  => UrlUtils::toAbsoluteUrl($fileInfo['fileName'])
            ];
        } catch (Exception $e) {
            throw new UploadException($e->getMessage());
        }
    }

    /**
     * 临时上传
     *
     * @param string $type (类型: image/video)
     * @return array
     * @throws UploadException
     * @author windy
     */
    #[ArrayShape(['name' => "string", 'ext' => "string", 'size' => "int", 'url' => "string"])]
    public static function temporary(string $type): array
    {
        try {
            $storageConfig = ['engine' => 'local', 'params' => []];
            $storageDriver = new StorageDriver($storageConfig);
            $storageDriver->validates($type);

            $file = request()->file('file');
            $name = $storageDriver->buildSaveName($file->getRealPath(), $file->extension());

            $filesystem = Filesystem::instance();
            $filesystem->disk('temporary')->putFileAS('', $file, $name);

            return [
                'name' => $file->getOriginalName(),
                'size' => $file->getSize(),
                'ext'  => $file->extension(),
                'url'  => UrlUtils::toAbsoluteUrl('temporary/' . $name)
            ];
        } catch (Exception $e) {
            throw new UploadException($e->getMessage());
        }
    }
}