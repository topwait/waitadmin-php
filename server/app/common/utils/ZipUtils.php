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

namespace app\common\utils;

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

/**
 * 压缩工具
 */
class ZipUtils
{
    /**
     * 压缩
     *
     * @param string $source (压缩的目录)
     * @param string $target (保存的路径)
     * @throws Exception
     * @author zero
     */
    public static function zip(string $source, string $target): void
    {
        $sourcePath = realpath($source);
        $zip = new ZipArchive();

        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }

        try {
            $zip->open($target, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            if (is_dir($sourcePath)) {
                $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($sourcePath),
                    RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $file) {
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($sourcePath) + 1);
                        $zip->addFile($filePath, $relativePath);
                    }
                }
            } else {
                $zip->addFile(root_path() . $source, $source);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        } finally {
            $zip->close();
        }
    }

    /**
     * 解压
     *
     * @author zero
     * @param string $zipFile (压缩包路径)
     * @param string $folderPath (解压的目录)
     * @return string (解压后路径)
     * @throws Exception
     */
    public static function unzip(string $zipFile, string $folderPath): string
    {
        if (!class_exists('ZipArchive')) {
            throw new Exception('ZinArchive not find');
        }

        $zip = new ZipArchive();
        try {
            $zip->open($zipFile);
        } catch (Exception $e) {
            $zip->close();
            throw new Exception('Unable to open the zip file  ' . $e->getMessage());
        }

        if (!is_dir($folderPath)) {
            @mkdir($folderPath, 0755);
        }

        $fileDir = trim($zip->getNameIndex(0), '/');

        try {
            $zip->extractTo($folderPath);
        } catch (Exception $e) {
            throw new Exception('Unable to extract the file ' . $e->getMessage());
        } finally {
            $zip->close();
        }

        return $fileDir;
    }
}