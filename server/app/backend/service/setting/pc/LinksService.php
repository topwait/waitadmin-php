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
use app\common\model\DevLinks;
use JetBrains\PhpStorm\ArrayShape;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 友情链接服务类
 */
class LinksService extends Service
{
    /**
     * 友情链接列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author zero
     */
    #[ArrayShape(['count' => "mixed", 'list' => "mixed"])]
    public static function lists(array $get): array
    {
        $model = new DevLinks();
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
     * 友情链接详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function detail(int $id): array
    {
        $model = new DevLinks();
        return $model->withoutField('is_delete,delete_time')
            ->where(['is_delete'=>0])
            ->where(['id'=>intval($id)])
            ->findOrFail()
            ->toArray();
    }

    /**
     * 友情链接新增
     *
     * @param array $post
     * @author zero
     */
    public static function add(array $post): void
    {
        DevLinks::create([
            'title'       => $post['title'],
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
     * 友情链接编辑
     *
     * @param array $post
     * @author zero
     */
    public static function edit(array $post): void
    {
        DevLinks::update([
            'title'       => $post['title'],
            'target'      => $post['target'],
            'url'         => $post['url']  ?? '',
            'sort'        => $post['sort'] ?? 0,
            'is_disable'  => $post['is_disable'],
            'update_time' => time()
        ], ['id'=>intval($post['id'])]);
    }

    /**
     * 友情链接删除
     *
     * @param array $ids
     * @author zero
     */
    public static function del(array $ids): void
    {
        DevLinks::update([
            'is_delete'   => 1,
            'delete_time' => time()
        ], [['id', 'in', $ids]]);
    }
}