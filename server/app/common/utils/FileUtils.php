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

namespace app\common\utils;

use FilesystemIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

/**
 * 文件工具
 */
class FileUtils
{
    /**
     * 创建目录
     *
     * @param string $dir      (目录路径)
     * @param int $permissions (创建权限)
     * @param bool $recursive  (递归创建)
     * @author zero
     */
    public static function mkdir(string $dir, int $permissions = 0777, bool $recursive = true): void
    {
        if (!file_exists($dir)) {
            self::mkdir(dirname($dir));
            mkdir($dir, $permissions, $recursive);
        }
    }

    /**
     * 删除目录
     *
     * @param string $dir (目录路径)
     * @return bool
     * @author zero
     */
    public static function rmdir(string $dir): bool
    {
        if (is_file($dir)) {
            @unlink($dir);
            return true;
        } else if (!is_dir($dir)){
            return true;
        }

        $dh = opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullPath = $dir . "/" . $file;
                if(!is_dir($fullPath)) {
                    @unlink($fullPath);
                } else {
                    self::rmdir($fullPath);
                }
            }
        }

        closedir($dh);
        return @rmdir($dir);
    }

    /**
     * 拷贝文件到指定目录
     *
     *
     * @param string $source (原始路径)
     * @param string $target (目录路径)
     * @param bool $isDelete (是否删除原文件)
     * @author zero
     */
    public static function copy(string $source, string $target, bool $isDelete = false): void
    {
        $source = str_replace('\\', '/', $source);
        $target = str_replace('\\', '/', $target);

        // 原始路径=>文件, 目标路径=>目录
        if (str_contains(basename($source), '.') && !str_contains(basename($target), '.')) {
            if (!is_dir($target)) {
                self::mkdir($target, 0755);
            }
            $target = str_ends_with($target, '/') ? $target : $target . '/';
            $result = @copy($source, $target.basename($source));
            if($isDelete && $result) {
                unlink($source);
            }
        }

        // 原始路径=>文件, 目标路径=>文件
        else if (str_contains(basename($source), '.') && str_contains(basename($target), '.')) {
            $dest = str_replace(basename($target), '', $target);
            if (!is_dir($dest)) {
                self::mkdir($dest, 0755);
            }

            $result = @copy($source, $target);
            if($isDelete && $result) {
                unlink($source);
            }
        }

        // 原始路径=>目录, 目标路径=>目录
        else {
            if (!is_dir($target)) {
                self::mkdir($target, 0755);
            }

            foreach (
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::SELF_FIRST
                ) as $item) {
                $subPathName = $iterator->getSubPathName();
                if ($item->isDir()) {
                    $sonDir = $target . $subPathName;
                    if (!is_dir($sonDir)) {
                        self::mkdir($sonDir, 0755);
                    }
                } else {
                    $result = @copy($item, $target . DIRECTORY_SEPARATOR . $subPathName);
                    if ($isDelete && $result) {
                        unlink($item);
                    }
                }
            }
        }
    }

    /**
     * 移动文件到指定目录
     *
     * @param string $source (原始路径)
     * @param string $target (目录路径)
     * @author zero
     */
    public static function move(string $source, string $target): void
    {
        $source = str_replace('\\', '/', $source);
        $target = str_replace('\\', '/', $target);
        self::copy($source, $target, true);
    }

    /**
     * 下载文件到本地目录
     *
     * @param string $url (远程链接)
     * @param String $saveTo (保存路径)
     */
    public static function download(string $url, String $saveTo): void
    {
        $basePath = str_replace(basename($saveTo), '', $saveTo);
        if (!file_exists($basePath)) {
            mkdir($basePath, 0775, true);
        }

        $content = file_get_contents($url);
        file_put_contents($saveTo, $content);
    }

    /**
     * 获取目录的大小
     *
     * @param string $dir (目录)
     * @return int
     * @author zero
     */
    public static function getDirSize(string $dir): int
    {
        if(!is_dir($dir)){
            return -1;
        }
        $handle = opendir($dir);
        $sizeResult = 0;
        while (false !== ($FolderOrFile = readdir($handle))) {
            if ($FolderOrFile != "." && $FolderOrFile != "..") {
                if (is_dir("$dir/$FolderOrFile")) {
                    $sizeResult += self::getDirSize("$dir/$FolderOrFile");
                } else {
                    $sizeResult += filesize("$dir/$FolderOrFile");
                }
            }
        }

        closedir($handle);
        return $sizeResult;
    }

    /**
     * 获取文件的大小
     *
     * @param string $path (路径)
     * @return int
     * @author zero
     */
    public static function getFileSize(string $path): int
    {
        $size = filesize($path);
        return $size === false ? -1 : $size;
    }

    /**
     * 获取文件的名称
     *
     * @param string $path (路径)
     * @return string
     * @author zero
     */
    public static function getFileName(string $path): string
    {
        $dir = str_replace('\\', '/', $path);
        $arr = explode('?', $dir);
        if ($arr) {
            $urls = explode('/', $arr[0]);
            return array_pop($urls);
        }

        return '';
    }

    /**
     * 获取文件扩展名
     *
     * @param string $path (路径)
     * @return string
     * @author zero
     */
    public static function getFileExt(string $path): string
    {
        return strrchr($path,'.') ? strtolower(substr(strrchr($path,'.'),1)) : '';
    }

    /**
     * 获取文件的列表
     *
     * @param string $path (路径)
     * @param string $type (类型)
     * @return array
     * @author zero
     */
    public static function getFileList(string $path, string $type=''): array
    {
        $list = [];
        foreach (scandir($path) as $file) {
            if ($file != '..' && $file != '.') {
                if (is_dir($path . '/' . $file)) {
                    $list[] = self::getFileList($path . '/' . $file,$type);
                } else {
                    if ($type=='') {
                        $list[] = $file;
                    } else {
                        $fileType = mime_content_type($path.'/'.$file);
                        if (str_contains($fileType, $type)) {
                            $list[] = $path.'/'.$file;
                        }
                    }
                }
            }
        }
        return $list;
    }

    /**
     * 验证是否有写入权限
     *
     * @param string $dir (路径)
     * @return bool
     * @author zero
     */
    public static function isWritable(string $dir): bool
    {
        if (DIRECTORY_SEPARATOR == '/' AND !@ini_get("safe_mode")) {
            return is_writable($dir);
        }

        if (!is_file($dir) OR ($fp = @fopen($dir, "r+")) === FALSE) {
            return false;
        }

        fclose($fp);
        return true;
    }
}