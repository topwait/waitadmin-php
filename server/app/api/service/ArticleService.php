<?php

namespace app\api\service;

use app\common\basics\Service;
use app\common\model\content\Article;
use app\common\model\content\ArticleCategory;
use JetBrains\PhpStorm\ArrayShape;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class ArticleService extends Service
{
    /**
     * 分类列表
     *
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function category(): array
    {
        $modelCategory = new ArticleCategory();
        $lists = $modelCategory->field(['id,name'])
            ->where(['is_disable'=>0])
            ->where(['is_delete'=>0])
            ->select()
            ->toArray();

        array_unshift($lists, ['id'=>0, 'name'=>'全部']);
        return $lists;
    }

    /**
     * 文章列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     */
    public static function lists(array $get): array
    {
        $where = [];
        if (!empty($get['cid']) && $get['cid']) {
            $where[] = ['cid', '=', intval($get['cid'])];
        }

        $modelArticle = new Article();
        return $modelArticle->field(['id,image,title,intro'])
            ->where($where)
            ->where(['is_delete'=>0])
            ->where(['is_show'=>1])
            ->order('id desc, browse desc')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page'  => 'page'
            ])->toArray();
    }

    public static function detail()
    {

    }
}