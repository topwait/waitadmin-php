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

namespace app\backend\service\setting\pc;

use app\common\basics\Service;
use app\common\model\DevBanner;
use JetBrains\PhpStorm\ArrayShape;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 轮播图服务类
 */
class BannerService extends Service
{
    /**
     * 轮播图列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author windy
     */
    #[ArrayShape(['count' => "mixed", 'list' => "mixed"])]
    public static function lists(array $get): array
    {
        $model = new DevBanner();
        $lists = $model->withoutField('is_delete,delete_time')
            ->where(['is_delete'=>0])
            ->order('sort desc, id desc')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page'  => 'page'
            ])->toArray();

        return ['count'=>$lists['total'], 'list'=>$lists['data']];
    }

    /**
     * 轮播图编辑
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author windy
     */
    public static function detail(int $id): array
    {
        $model = new DevBanner();
        return $model->withoutField('is_delete,delete_time')
            ->where(['is_delete'=>0])
            ->where(['id'=>intval($id)])
            ->findOrFail()
            ->toArray();
    }

    /**
     * 轮播图新增
     *
     * @param array $post
     * @author windy
     */
    public static function add(array $post): void
    {
        DevBanner::create([
            'position'    => $post['position'],
            'title'       => $post['title'],
            'image'       => $post['image'],
            'target'      => $post['target'],
            'url'         => $post['url']  ?? '',
            'sort'        => $post['sort'] ?? 0,
            'is_disable'  => $post['is_disable'],
            'is_delete'   => 0,
            'create_time' => time(),
            'update_time' => time()
        ]);
    }

    /**
     * 轮播图编辑
     *
     * @param array $post
     * @author windy
     */
    public static function edit(array $post): void
    {
        DevBanner::update([
            'position'    => $post['position'],
            'title'       => $post['title'],
            'image'       => $post['image'],
            'target'      => $post['target'],
            'url'         => $post['url']  ?? '',
            'sort'        => $post['sort'] ?? 0,
            'is_disable'  => $post['is_disable'],
            'update_time' => time()
        ], ['id'=>intval($post['id'])]);
    }

    /**
     * 轮播图删除
     *
     * @param array $ids
     * @author windy
     */
    public static function del(array $ids): void
    {
        DevBanner::update([
            'is_delete'   => 1,
            'delete_time' => time()
        ], [['id', 'in', $ids]]);
    }
}