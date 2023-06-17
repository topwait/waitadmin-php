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

namespace app\backend\service\content;

use app\common\basics\Service;
use app\common\model\article\Article;
use app\common\utils\AttachUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 文章服务类
 */
class ArticleService extends Service
{
    /**
     * 文章列表
     *
     * @return array
     * @throws DbException
     * @author zero
     */
    public static function lists(): array
    {
        self::setSearch([
            '%like%'   => ['title'],
            '='        => ['status@is_show'],
            'datetime' => ['datetime@create_time'],
        ]);

        $model = new Article();
        $lists = $model
            ->withoutField('is_delete,delete_time')
            ->with(['category'])
            ->where(['is_delete'=>0])
            ->where(self::$searchWhere)
            ->order('sort desc, id desc')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page'  => 'page'
            ])->toArray();

        foreach ($lists['data'] as &$item) {
            $item['category'] = $item['category']['name'] ?? '无';
        }

        return ['count'=>$lists['total'], 'list'=>$lists['data']] ?? [];
    }

    /**
     * 文章详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function detail(int $id): array
    {
        $model = new Article();
        return $model->withoutField('is_delete,delete_time')
            ->where(['id'=> $id])
            ->where(['is_delete'=>0])
            ->findOrFail()
            ->toArray();
    }

    /**
     * 文章新增
     *
     * @param array $post
     * @author zero
     */
    public static function add(array $post): void
    {
        AttachUtils::markCreate($post, ['image', 'content']);
        Article::create([
            'cid'          => $post['cid'],
            'title'        => $post['title'],
            'sort'         => $post['sort']    ?? 0,
            'image'        => $post['image']   ?? '',
            'intro'        => $post['intro']   ?? '',
            'content'      => $post['content'],
            'is_topping'   => $post['is_topping'],
            'is_recommend' => $post['is_recommend'],
            'is_show'      => $post['is_show'],
            'create_time'  => time(),
            'update_time'  => time()
        ]);
    }

    /**
     * 文章编辑
     *
     * @param array $post
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function edit(array $post): void
    {
        $model = new Article();
        $article = $model->field('id,image,content')
            ->where(['id'=>intval($post['id'])])
            ->where(['is_delete'=>0])
            ->findOrFail()
            ->toArray();

        AttachUtils::markUpdate($article, $post, ['image', 'content']);
        Article::update([
            'cid'          => $post['cid'],
            'title'        => $post['title'],
            'sort'         => $post['sort']    ?? 0,
            'intro'        => $post['intro']   ?? '',
            'image'        => $post['image']   ?? '',
            'content'      => $post['content'] ?? '',
            'is_topping'   => $post['is_topping'],
            'is_recommend' => $post['is_recommend'],
            'is_show'      => $post['is_show'],
            'update_time'  => time()
        ], ['id'=>intval($post['id'])]);
    }

    /**
     * 文章删除
     *
     * @param array $ids
     * @author zero
     */
    public static function del(array $ids): void
    {
        Article::update([
            'is_delete'   => 1,
            'update_time' => time()
        ], array(['id', 'in', $ids]));
    }
}