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

namespace app\backend\service;


use app\common\basics\Service;
use app\common\enums\AttachEnum;
use app\common\exception\UploadException;
use app\common\model\attach\Attach;
use app\common\service\storage\StorageDriver;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use think\facade\Filesystem;

/**
 * 上传服务类
 *
 * Class UploadService
 * @package app\backend\service
 */
class UploadService extends Service
{
    /**
     * 附件上传
     *
     * @param string $type (类型: image/video)
     * @param int $cid (所属分类)
     * @param int $uid (所属用户)
     * @return array
     * @throws UploadException
     * @author windy
     */
    #[ArrayShape(['id' => "mixed", 'name' => "mixed", 'ext' => "mixed", 'size' => "mixed", 'url' => "string"])]
    public static function upload(string $type, int $cid, int $uid): array
    {
        try {
            // 存储引擎
            $engine = ConfigUtils::get('storage', 'default', 'local');
            $params = ConfigUtils::get('storage', $engine, []);

            // 上传调用
            $storageDriver = new StorageDriver(['engine'=>$engine, 'params'=>$params]);
            $fileInfo = $storageDriver->upload($type, 'attach');

            // 记录信息
            $attach = Attach::create([
                'uid'       => $uid,
                'cid'       => $cid,
                'file_type' => AttachEnum::getCodeByMsg($type),
                'file_path' => $fileInfo['fileName'],
                'file_name' => $fileInfo['name'],
                'file_ext'  => $fileInfo['ext'],
                'file_size' => $fileInfo['size']
            ]);

            // 返回信息
            return [
                'id'   => $attach['id'],
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
            $storageDriver = new StorageDriver(['engine' => 'local', 'params' => []]);
            $storageDriver->validates($type);

            $file = request()->file('file');
            $name = $storageDriver->buildSaveName($file->getRealPath(), $file->extension());

            $filesystem = Filesystem::instance();
            $filesystem->disk('temporary')->putFileAS('', $file, $name);

            return [
                'name' => $file->getOriginalName(),
                'ext'  => $file->extension(),
                'size' => $file->getSize(),
                'url'  => UrlUtils::toAbsoluteUrl('temporary/' . $name)
            ];
        } catch (Exception $e) {
            throw new UploadException($e->getMessage());
        }
    }
}