<?php

namespace app\frontend\service;

use app\common\basics\Service;
use app\common\exception\UploadException;
use app\common\service\storage\StorageDriver;
use app\common\utils\UrlUtils;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use think\facade\Filesystem;

class UploadService extends Service
{
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
            $params = [];
            $storageConfig = ['engine' => 'local', 'params' => $params];
            $storageDriver = new StorageDriver($storageConfig);
            $storageDriver->validates($type);

            $file = request()->file('file');
            $real = $file->getRealPath();
            $name = $storageDriver->buildSaveName($real, $file->extension());

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