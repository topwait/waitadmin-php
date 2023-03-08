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

namespace app\backend\service\diy;

use app\common\basics\Service;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;

class PersonService extends Service
{
    /**
     * 功能列表
     *
     * @return array
     * @author windy
     */
    public static function lists(): array
    {
        $lists = (array) ConfigUtils::get('diy', 'person', []);
        foreach ($lists as &$item) {
            $item['image'] = UrlUtils::toAbsoluteUrl($item['image']);
        }

        return $lists;
    }

    /**
     * 功能详情
     *
     * @param int $id
     * @return array
     * @author windy
     */
    public static function detail(int $id): array
    {
        $detail = [];
        $lists = (array) ConfigUtils::get('diy', 'person', []);
        foreach ($lists as $item) {
            if ($item['id'] == $id) {
                $item['image'] = UrlUtils::toAbsoluteUrl($item['image']);
                $detail = $item;
                break;
            }
        }

        return (array) $detail;
    }

    /**
     * 功能新增
     *
     * @param array $post
     * @author windy
     */
    public static function add(array $post): void
    {
        $lists = (array) ConfigUtils::get('diy', 'person', []);
        $lists[] = [
            'name'      => $post['name'],
            'image'     => UrlUtils::toRelativeUrl($post['image']),
            'sort'      => intval($post['sort']),
            'link_type' => $post['link_type'],
            'link_url'  => $post['link_url'],
            'is_show'   => $post['is_show'],
        ];

        usort($lists, function ($a, $b) {
            return $a['sort'] - $b['sort'];
        });

        $i = 1;
        foreach ($lists as &$item) {
            $item['id'] = $i;
            $i++;
        }

        ConfigUtils::set('diy', 'person', $lists);
    }

    /**
     * 功能编辑
     *
     * @param array $post
     * @author windy
     */
    public static function edit(array $post): void
    {
        $lists = (array) ConfigUtils::get('diy', 'person', []);
        foreach ($lists as &$item) {
            if ($item['id'] == $post['id']) {
                $item['name']      = $post['name'];
                $item['image']     = UrlUtils::toRelativeUrl($post['image']);
                $item['sort']      = intval($post['sort']);
                $item['link_type'] = $post['link_type'];
                $item['link_url']  = $post['link_url'];
                $item['is_show']   = $post['is_show'];
                break;
            }
        }

        usort($lists, function ($a, $b) {
            return $a['sort'] - $b['sort'];
        });

        $i = 1;
        foreach ($lists as &$item) {
            $item['id'] = $i;
            $i++;
        }

        ConfigUtils::set('diy', 'person', $lists);
    }

    /**
     * 功能删除
     *
     * @param int $id
     * @author windy
     */
    public static function del(int $id)
    {
        $data = [];
        $lists = (array) ConfigUtils::get('diy', 'person', []);
        foreach ($lists as $item) {
            if ($item['id'] != $id) {
                $data[] = $item;
            }
        }

        usort($data, function ($a, $b) {
            return $a['sort'] - $b['sort'];
        });

        $i = 1;
        foreach ($data as &$item) {
            $item['id'] = $i;
            $i++;
        }

        ConfigUtils::set('diy', 'person', $data);
    }
}