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

namespace app\backend\service;

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
     * 永远存储
     *
     * @param string $type (类型: picture/video/document/package)
     * @param int $hide (是否隐藏: 0=否, 1=是)
     * @param int $cid  (所属分类)
     * @param int $uid  (所属用户)
     * @return array
     * @throws UploadException
     * @author zero
     */
    public static function permanent(string $type, int $hide, int $cid, int $uid): array
    {
        try {
            // 上传调用
            $storageDriver = new StorageDriver();
            $fileInfo = $storageDriver->upload($type);

            // 记录信息
            $attach = Attach::create([
                'uid'       => $uid,
                'cid'       => $cid,
                'file_type' => AttachEnum::getCodeByMsg($type),
                'file_path' => $fileInfo['fileName'],
                'file_name' => $fileInfo['name'],
                'file_ext'  => $fileInfo['ext'],
                'file_size' => $fileInfo['size'],
                'is_user'   => 0,
                'is_attach' => !$hide
            ]);

            $icon = '';
            switch ($attach['file_type']) {
                case AttachEnum::PICTURE:
                case AttachEnum::VIDEO:
                    $icon = $attach['file_path'];
                    break;
                case AttachEnum::PACKAGE:
                case AttachEnum::DOCUMENT:
                    $ext = $attach['file_ext'];
                    $packageExt = config('project.uploader.package')['ext'];
                    $documentExt = config('project.uploader.document')['ext'];
                    if (!in_array($ext, $packageExt) && !in_array($ext, $documentExt)) {
                        $ext = 'unknown';
                    }
                    $icon = '/static/backend/images/attach/'.$ext.'.png';
                    break;
            }

            // 返回信息
            return [
                'id'   => $attach['id'],
                'name' => $fileInfo['name'],
                'ext'  => $fileInfo['ext'],
                'size' => $fileInfo['size'],
                'icon' => UrlUtils::toAbsoluteUrl($icon),
                'url'  => UrlUtils::toAbsoluteUrl($fileInfo['fileName'])
            ] ?? [];
        } catch (Exception $e) {
            throw new UploadException($e->getMessage());
        }
    }

    /**
     * 临时存储
     *
     * @param string $type (类型: picture/video/document/package)
     * @return array
     * @throws UploadException
     * @author zero
     */
    public static function temporary(string $type): array
    {
        try {
            $storageDriver = new StorageDriver([], 'local');
            $storageDriver->validates($type);

            $file = request()->file('file');
            $name = $storageDriver->buildSaveName($file->getRealPath(), $file->extension());

            $filesystem = Filesystem::instance();
            $filesystem->disk('temporary')->putFileAS('', $file, $name);

            $icon = '';
            switch ($type) {
                case AttachEnum::PICTURE:
                case AttachEnum::VIDEO:
                    $icon = UrlUtils::toAbsoluteUrl('temporary/' . $name);
                    break;
                case AttachEnum::PACKAGE:
                case AttachEnum::DOCUMENT:
                    $ext = strtolower($file->getExtension());
                    $packageExt = config('project.uploader.package')['ext'];
                    $documentExt = config('project.uploader.document')['ext'];
                    if (!in_array($ext, $packageExt) && !in_array($ext, $documentExt)) {
                        $ext = 'unknown';
                    }
                    $icon = '/static/backend/images/attach/'.$ext.'.png';
                    break;
            }

            return [
                'name' => $file->getOriginalName(),
                'ext'  => $file->extension(),
                'size' => $file->getSize(),
                'icon' => UrlUtils::toAbsoluteUrl($icon),
                'url'  => UrlUtils::toAbsoluteUrl('temporary/' . $name)
            ] ?? [];
        } catch (Exception $e) {
            throw new UploadException($e->getMessage());
        }
    }
}