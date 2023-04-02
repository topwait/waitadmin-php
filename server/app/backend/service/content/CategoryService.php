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
use app\common\exception\OperateException;
use app\common\model\article\Article;
use app\common\model\article\ArticleCategory;
use app\common\utils\AjaxUtils;
use JetBrains\PhpStorm\ArrayShape;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 文章分类服务类
 */
class CategoryService extends Service
{
    /**
     * 所有文章分类
     *
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    public static function all(): array
    {
        $model = new ArticleCategory();
        return $model
            ->field('id,name')
            ->where(['is_delete'=>0])
            ->order('sort desc, id desc')
            ->select()->toArray();
    }

    /**
     * 文章分类列表
     *
     * @return array
     * @throws DbException
     * @author windy
     */
    #[ArrayShape(['count' => "int", 'list' => "array"])]
    public static function lists(): array
    {
        self::setSearch([
            '%like%' => ['name'],
            '='      => ['status@is_disable']
        ]);

        $model = new ArticleCategory();
        $lists = $model
            ->withoutField('is_delete,delete_time')
            ->where(['is_delete'=>0])
            ->where(self::$searchWhere)
            ->order('sort desc, id desc')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page'  => 'page'
            ])->toArray();

        return ['count'=>$lists['total'], 'list'=>$lists['data']];
    }

    /**
     * 文章分类详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author windy
     */
    public static function detail(int $id): array
    {
        $model = new ArticleCategory();
        return $model->withoutField('is_delete,delete_time')
            ->where(['id'=>intval($id)])
            ->where(['is_delete'=>0])
            ->findOrFail()
            ->toArray();
    }

    /**
     * 文章分类新增
     *
     * @param array $post
     * @author windy
     */
    public static function add(array $post): void
    {
        ArticleCategory::create([
            'name'        => $post['name'],
            'sort'        => $post['sort'] ?? 0,
            'is_disable'  => $post['is_disable'] ?? 0,
            'is_delete'   => 0,
            'create_time' => time(),
            'update_time' => time()
        ]);
    }

    /**
     * 文章分类编辑
     *
     * @param array $post
     * @author windy
     */
    public static function edit(array $post): void
    {
        ArticleCategory::update([
            'name'        => $post['name'],
            'sort'        => $post['sort'] ?? 0,
            'is_disable'  => $post['is_disable'] ?? 0,
            'update_time' => time()
        ], ['id'=>intval($post['id'])]);
    }

    /**
     * 文章分类删除
     *
     * @param int $id
     * @throws OperateException
     * @author windy
     */
    public static function del(int $id): void
    {
        $modelArticle = new Article();
        $modelArticle->checkDataAlreadyExist([
            ['cid', '=', $id],
            ['is_delete', '=', 0]
        ], '当前类型已被使用!');

        ArticleCategory::update([
            'is_delete'   => 1,
            'delete_time' => time()
        ], ['id'=>intval($id)]);
    }
}