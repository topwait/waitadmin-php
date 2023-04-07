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

namespace app\frontend\service;

use app\common\basics\Service;
use app\common\exception\UploadException;
use app\common\service\storage\StorageDriver;
use app\common\utils\UrlUtils;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use think\facade\Filesystem;

/**
 * 上传服务类
 */
class UploadService extends Service
{
    /**
     * 临时上传
     *
     * @param string $type (类型: image/video)
     * @return array
     * @throws UploadException
     * @author zero
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