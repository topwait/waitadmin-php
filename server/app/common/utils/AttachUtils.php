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

/**
 * 附件工具
 *
 * Class AttachUtils
 * @package app\common\utils
 */
class AttachUtils
{
    /**
     * 标记附件创建
     *
     * @param string $dest (迁到路径)
     * @param array $post  (提交数据)
     * @param array $keys  (处理的键)
     * @return array       (处理的键)
     * @author zero
     */
    public static function markCreate(string $dest, array $post, array $keys): array
    {
        if (!str_ends_with($dest, '/')) {
            $dest = $dest . '/';
        }

        $result = [];
        $target = str_replace('\\', '/', public_path() . $dest);

        foreach ($keys as $key) {
            if (empty($post[$key]) || !$post[$key]) {
                continue;
            }

            // 数组类型
            if (is_array($post[$key])) {
                foreach ($post[$key] as $url) {
                    $name = self::handleSrc($url, $target);
                    $result[$key] = $dest.$name;
                }
            }

            // 文本类型
            else if (str_starts_with($post[$key], 'http'.'://') || str_starts_with($post[$key], 'https://')) {
                $url  = UrlUtils::toRelativeUrl((string)$post[$key]);
                $name = self::handleSrc($url, $target);
                $result[$key] = $dest.$name;
            }

            // 富文本型
            else {
                $images = self::fetchSrc($post[$key]);
                foreach ($images as $url) {
                    $name = self::handleSrc($url, $target);
                    $post[$key] = str_replace($url, $dest.$name, $post[$key]);
                }
                $result[$key] = self::relativeSrc($post[$key]);
            }
        }

        return $result;
    }

    /**
     * 标记附件更新
     *
     * @param string $dest (迁到路径)
     * @param array $post  (提交数据)
     * @param array $objs  (原始数据)
     * @param array $keys  (处理的键)
     * @return array       (处理结果)
     * @author zero
     */
    public static function markUpdate(string $dest, array $post, array $objs, array $keys): array
    {
        if (!str_ends_with($dest, '/')) {
            $dest = $dest . '/';
        }

        $target = str_replace('\\', '/', public_path() . $dest);
        $handleRestArray = [];
        $stayDeleteArray = [];

        // 处理素材
        foreach ($keys as $item) {
            $arr = explode('@', $item);
            $postKey = trim($arr[0]);
            $objsKey = count($arr)>=2 ? trim($arr[1]) : trim($arr[0]);

            if (empty($post[$postKey]) || !$post[$objsKey]) continue;
            $handleRestArray[$postKey] = $post[$postKey];

            // 数组类型
            if (is_array($post[$postKey])) {
                $objs[$objsKey] = is_array($objs[$objsKey]) ? $objs[$objsKey] : explode(',', $objs[$objsKey]);
                $stayDelete = array_diff($objs[$objsKey], $post[$postKey]);
                $stayInsert = array_diff($post[$postKey], $objs[$objsKey]);

                $newResult = [];
                $stayDeleteArray = array_merge($stayDeleteArray, $stayDelete);
                foreach ($stayInsert as $url) {
                    try {
                        $name = self::handleSrc($url, $target);
                        $newResult[] = $dest.$name;
                    } catch (Exception) {
                        continue;
                    }
                }

                foreach ($objs[$objsKey] as $url) {
                    if (!in_array($url, $stayDelete)) {
                        $newResult[] = $url;
                    }
                }
                $handleRestArray[$postKey] = $newResult;
            }

            // HTTP类型
            else if (str_starts_with($post[$postKey], 'http'.'://') || str_starts_with($post[$postKey], 'https://')) {
                $newUrl = UrlUtils::toRelativeUrl($post[$postKey]);
                $oldUrl = UrlUtils::toRelativeUrl($objs[$objsKey]);
                $handleRestArray[$postKey] = $oldUrl;
                try {
                    if ($newUrl != $oldUrl) {
                        $name = self::handleSrc($newUrl, $target);
                        $handleRestArray[$postKey] = $dest.$name;
                        $stayDeleteArray[] = $oldUrl;
                    }
                } catch (Exception) {
                    continue;
                }
            }

            // 富文本类型
            else {
                $newArr = self::fetchSrc($post[$postKey]);
                $oldArr = self::fetchSrc($objs[$objsKey]);
                $stayDelete = array_diff($oldArr, $newArr);
                $stayInsert = array_diff($newArr, $oldArr);
                $stayDeleteArray = array_merge($stayDeleteArray, $stayDelete);
                try {
                    foreach ($stayInsert as $url) {
                        $name = self::handleSrc($url, $target);
                        $handleRestArray[$postKey] = str_replace($url, $dest.$name, $post[$postKey]);
                    }
                } catch (Exception) {
                    continue;
                }
            }
        }

        // 处理删除
        if (!empty($stayDeleteArray)) {
            foreach ($stayDeleteArray as $url) {
                @unlink(UrlUtils::toRoot($url));
            }
        }

        // 处理结果
        return $handleRestArray;
    }

    /**
     * Src地址查询
     * 从富文本中取出所有Src
     *
     * @param string $content
     * @return array
     * @author zero
     */
    public static function fetchSrc(string $content): array
    {
        preg_match_all( '/(src|SRC)=("[^"]*")/i', $content, $match);

        $urls = [];
        foreach ($match[2] as $url) {
            $path = trim($url, '"');
            $urls[] = UrlUtils::toRelativeUrl($path);
        }

        return array_unique($urls);
    }

    /**
     * Src转相对地址
     * 富文本内容里的Src转成相对链接
     *
     * @param string $content
     * @return string
     * @author zero
     */
    public static function relativeSrc(string $content): string
    {
        $engine = ConfigUtils::get('storage', 'default', 'local');
        // 本地路径
        $domain  = request()->domain() . '/';
        $content = str_replace($domain, '', $content);
        // 云端路径
        if ($engine != 'local') {
            $config = ConfigUtils::get('storage', $engine, []);
            $domain = $config['domain'] ?? 'http'.'://';
            $content = str_replace($domain, '', $content);
        }
        return $content;
    }

    /**
     * Src转绝对地址
     * 富文本内容里的Src转成绝对链接
     *
     * @param string $content
     * @return string
     * @author zero
     */
    public static function absoluteSrc(string $content): string
    {
        $engine = ConfigUtils::get('storage', 'default', 'local');
        if ($engine !== 'local') {
            $config = ConfigUtils::get('storage', $engine, []);
            $domain = $config['domain'] ?? 'http'.'://';
        } else {
            $domain = request()->domain() . '/';
        }

        $preg = '/(<.*?src=")(?!http|https)(.*?)(".*?>)/is';
        return preg_replace($preg, "\${1}$domain\${2}\${3}", $content);
    }

    /**
     * 处理附件位置
     *
     * @param string $url    (文件路径)
     * @param string $target (目标路径)
     * @return string
     * @author zero
     */
    private static function handleSrc(string $url, string $target): string
    {
        $url  = UrlUtils::toRelativeUrl($url);
        $name = self::buildSaveName($url);
        if (file_exists($target.$name)) {
            return $name;
        }
        if (str_starts_with($url, 'temporary')) {
            FileUtils::move(UrlUtils::toRoot($url), $target.$name);
        } else {
            FileUtils::copy(UrlUtils::toRoot($url), $target.$name);
        }
        return $name;
    }

    /**
     * 生成文件名
     *
     * @param string $realPath (临时路径)
     * @return string          (日期名称)
     * @author zero
     */
    private static function buildSaveName(string $realPath): string
    {
        $ext = FileUtils::getFileExt($realPath);
        return
             date('His')
            . substr(md5($realPath), 0, 8)
            . substr(md5(microtime()), 5, 10)
            . str_pad(strval(rand(0, 9999)), 5, '0', STR_PAD_LEFT)
            . ".$ext";
    }
}