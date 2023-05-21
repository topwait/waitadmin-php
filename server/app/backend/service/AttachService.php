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
use app\common\exception\OperateException;
use app\common\model\attach\Attach;
use app\common\model\attach\AttachCate;
use app\common\utils\UrlUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 附件服务类
 */
class AttachService extends Service
{
    /**
     * 文件列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author zero
     */
    public static function lists(array $get): array
    {
        $where = [];
        if (isset($get['cid']) && $get['cid'] >= 0) {
            $where[] = ['cid', '=', intval($get['cid'])];
        }

        self::setSearch([
            '='      => ['type@file_type'],
            '%like%' => ['keyword@file_name']
        ]);

        $model = new Attach();
        $lists = $model
            ->field('id,file_type,file_ext,file_name,file_path,create_time')
            ->where($where)
            ->where(self::$searchWhere)
            ->where(['is_attach'=>1])
            ->where(['is_delete'=>0])
            ->order('id desc')
            ->withAttr('file_path', function ($value) {
                return UrlUtils::toAbsoluteUrl($value);
            })
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 10,
                'var_page'  => 'page'
            ])->toArray();

        foreach ($lists['data'] as &$item) {
            switch ($item['file_type']) {
                case AttachEnum::PICTURE:
                case AttachEnum::VIDEO:
                    $item['icon'] = $item['file_path'];
                    break;
                case AttachEnum::PACKAGE:
                    $ext = !empty($item['file_ext']) ? $item['file_ext'] : 'ot';
                    $item['icon'] = '/static/backend/images/attach/package/'.$ext.'.png';
                    break;
                case AttachEnum::DOCUMENT:
                    $ext = !empty($item['file_ext']) ? $item['file_ext'] : 'unknown';
                    $item['icon'] = '/static/backend/images/attach/document/'.$ext.'.png';
                    break;
            }
        }

        return $lists;
    }

    /**
     * 文件命名
     *
     * @param array $post
     * @author zero
     */
    public static function rename(array $post): void
    {
        Attach::update([
            'file_name'   => $post['title'],
            'update_time' => time()
        ], ['id'=>intval($post['id'])]);
    }

    /**
     * 文件移动
     *
     * @param array $post
     * @author zero
     */
    public static function move(array $post): void
    {
        Attach::update([
            'cid'         => intval($post['cid']),
            'update_time' => time()
        ], [['id', 'in', $post['ids']]]);
    }

    /**
     * 文件删除
     *
     * @param array $ids
     * @author zero
     */
    public static function del(array $ids): void
    {
        Attach::update([
            'is_delete'   => 1,
            'delete_time' => time()
        ], [['id', 'in', $ids]]);
    }

    /**
     * 分组列表
     *
     * @return array
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function cateLists(): array
    {
        self::setSearch([
            '=' => ['type']
        ]);

        $model = new AttachCate();
        return $model->field('id,name')
            ->where(self::$searchWhere)
            ->where(['is_delete'=>0])
            ->select()
            ->toArray();
    }

    /**
     * 分组创建
     *
     * @param array $post
     * @author zero
     */
    public static function cateCreate(array $post): void
    {
        AttachCate::create([
            'type' => $post['type'],
            'name' => $post['name'],
            'create_time' => time(),
            'update_time' => time(),
        ]);
    }

    /**
     * 分组命名
     *
     * @param array $post
     * @throws OperateException
     * @author zero
     */
    public static function cateRename(array $post): void
    {
        $modelAttachCate = new AttachCate();
        $modelAttachCate->checkDataDoesNotExist([
            'id'   => intval($post['id']),
            'type' => intval($post['type']),
            'is_delete' => 0
        ]);

        AttachCate::update([
            'name' => $post['name'],
            'update_time' => time(),
        ], ['id'=>intval($post['id']), 'type'=>intval($post['type'])]);
    }

    /**
     * 分组删除
     *
     * @param array $post
     * @return void
     * @throws OperateException
     * @author zero
     */
    public static function cateDelete(array $post): void
    {
        $modelAttachCate = new AttachCate();
        $modelAttachCate->checkDataDoesNotExist([
            'id'   => intval($post['id']),
            'type' => intval($post['type']),
            'is_delete' => 0
        ]);

        AttachCate::update([
            'is_delete'   => 1,
            'delete_time' => time(),
        ], ['id'=>intval($post['id']), 'type'=>intval($post['type'])]);
    }
}