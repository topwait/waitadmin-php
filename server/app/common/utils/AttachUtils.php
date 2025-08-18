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

use app\common\model\attach\Attach;

/**
 * 附件工具
 */
class AttachUtils
{
    /**
     * 标记附件引用
     * PS: 附件引用+1
     *
     * @param array $post  (提交数据)
     * @param array $keys  (处理的键: postKey@sqlKey)
     * @author zero
     */
    public static function markCreate(array $post, array $keys): void
    {
        // 读取路径
        foreach ($keys as $key) {
            // 跳过空值
            if (empty($post[$key]) || $post[$key] == '') {
                continue;
            }

            // 数组类型
            if (is_array($post[$key])) {
                foreach ($post[$key] as $url) {
                    self::_updateQuote($url, 'inc');
                }
            }

            // 文本类型
            else if (str_starts_with($post[$key], 'http'.'://') || str_starts_with($post[$key], 'https://')) {
                self::_updateQuote((string)$post[$key], 'inc');
            }

            // 富文本型
            else {
                $urls = UrlUtils::editorFetchSrc($post[$key]);
                foreach ($urls as $url) {
                    self::_updateQuote($url, 'inc');
                }
            }
        }
    }

    /**
     * 标记附件更新
     * PS: 如果附件引用+1,否侧取消引用-1
     *
     * @param array $objs  (原始数据)
     * @param array $post  (提交数据)
     * @param array $keys  (处理的键: postKey@sqlKey)
     * @author zero
     */
    public static function markUpdate(array $objs, array $post, array $keys): void
    {
        // 处理素材
        foreach ($keys as $item) {
            $arr = explode('@', $item);
            $postKey = trim($arr[0]);
            $objsKey = count($arr)>=2 ? trim($arr[1]) : trim($arr[0]);

            // 空值处理
            if (empty($post[$postKey]) && empty($objs[$objsKey])) {
                continue;
            }

            // 清空处理
            if (empty($post[$postKey]) && !empty($objs[$objsKey])) {
                $editors = UrlUtils::editorFetchSrc($objs[$objsKey]);
                if (empty($editors) && !is_array($objs[$objsKey])) {
                    $editors = explode(',', $objs[$objsKey]);
                }

                foreach ($editors as $url) {
                    $url = UrlUtils::toRelativeUrl($url);
                    self::_updateQuote($url, 'dec');
                }
                continue;
            }

            // 数组类型
            if (is_array($post[$postKey])) {
                $objs[$objsKey] = is_array($objs[$objsKey]) ? $objs[$objsKey] : explode(',', $objs[$objsKey]);
                $stayDelete = array_diff($objs[$objsKey], $post[$postKey]);
                $stayInsert = array_diff($post[$postKey], $objs[$objsKey]);

                foreach ($objs[$objsKey] as $url) {
                    if (!in_array($url, $stayDelete)) {
                        $stayInsert[] = $url;
                    }
                }

                foreach ($stayInsert as $url) {
                    self::_updateQuote($url, 'inc');
                }

                foreach ($stayDelete as $url) {
                    self::_updateQuote($url, 'dec');
                }
            }

            // HTTP类型
            else if (str_starts_with($post[$postKey], 'http'.'://') || str_starts_with($post[$postKey], 'https://')) {
                $newUrl = UrlUtils::toRelativeUrl($post[$postKey]); // 提交附件
                $oldUrl = UrlUtils::toRelativeUrl($objs[$objsKey]); // 原始附件

                if ($newUrl != $oldUrl) {
                    self::_updateQuote($newUrl, 'inc');
                    self::_updateQuote($oldUrl, 'dec');
                }
            }

            // 富文本类型
            else {
                $newArr = UrlUtils::editorFetchSrc($post[$postKey]);  // 提交附件
                $oldArr = UrlUtils::editorFetchSrc($objs[$objsKey]);  // 原始附件
                $stayDelete = array_diff($oldArr, $newArr); // 待删除的
                $stayInsert = array_diff($newArr, $oldArr); // 待引用的
                foreach ($stayInsert as $url) {
                    self::_updateQuote($url, 'inc');
                }

                foreach ($stayDelete as $url) {
                    self::_updateQuote($url, 'dec');
                }
            }
        }
    }

    /**
     * 更新附件引用
     *
     * @param string $url
     * @param string $scene
     * @author zero
     */
    private static function _updateQuote(string $url, string $scene): void
    {
        $modelAttach = new Attach();
        $attach = $modelAttach
            ->field(['id,quote,is_delete'])
            ->where(['file_path'=>$url])
            ->order('id')
            ->findOrEmpty()
            ->toArray();

        if ($attach) {
            if ($scene === 'dec' && $attach['quote'] > 0) {
                Attach::update([
                    'quote' => ['dec', 1],
                    'update_time' => time()
                ], ['id'=>$attach['id']]);
            } else {
                Attach::update([
                    'quote' => ['inc', 1],
                    'update_time' => time()
                ], ['id' => $attach['id']]);
            }
        }
    }
}