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

namespace app\api\service;

use app\common\basics\Service;
use app\common\enums\AttachEnum;
use app\common\exception\UploadException;
use app\common\model\attach\Attach;
use app\common\service\storage\StorageDriver;
use app\common\utils\UrlUtils;
use Exception;
use think\facade\Filesystem;

/**
 * 上传服务类
 */
class UploadService extends Service
{
    /**
     * 永久存储
     *
     * @param string $type (类型: picture/video/document/package)
     * @param int $userId  (用户ID)
     * @return array
     * @throws UploadException
     * @author zero
     */
    public static function permanent(string $type, int $userId): array
    {
        try {
            // 上传调用
            $storageDriver = new StorageDriver();
            $fileInfo = $storageDriver->upload($type);

            // 记录信息
            Attach::create([
                'cid'       => 0,
                'uid'       => $userId,
                'file_type' => AttachEnum::getCodeByMsg($type),
                'file_path' => $fileInfo['fileName'],
                'file_name' => $fileInfo['name'],
                'file_ext'  => $fileInfo['ext'],
                'file_size' => $fileInfo['size'],
                'is_user'   => 1,
                'is_attach' => 0
            ]);

            // 返回信息
            return [
                'name' => $fileInfo['name'],
                'ext'  => $fileInfo['ext'],
                'size' => $fileInfo['size'],
                'path' => $fileInfo['fileName'],
                'url'  => UrlUtils::toAbsoluteUrl($fileInfo['fileName'])
            ] ?? [];
        } catch (Exception $e) {
            throw new UploadException($e->getMessage());
        }
    }

    /**
     * 临时上传
     *
     * @param string $type (类型: picture/video/document/package)
     * @return array
     * @throws UploadException
     * @author zero
     */
    public static function temporary(string $type): array
    {
        try {
            // 设置引擎
            $storageDriver = new StorageDriver([], 'local');
            $storageDriver->validates($type);

            // 获取文件
            $file = request()->file('file');
            $name = $storageDriver->buildSaveName($file->getRealPath(), $file->extension());

            // 保存文件
            $filesystem = Filesystem::instance();
            $filesystem->disk('temporary')->putFileAS('', $file, $name);

            // 返回信息
            return [
                'name' => $file->getOriginalName(),
                'size' => $file->getSize(),
                'ext'  => $file->extension(),
                'url'  => UrlUtils::toAbsoluteUrl('temporary/' . $name)
            ] ?? [];
        } catch (Exception $e) {
            throw new UploadException($e->getMessage());
        }
    }
}