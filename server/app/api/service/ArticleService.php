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

namespace app\api\service;

use app\common\basics\Service;
use app\common\model\article\Article;
use app\common\model\article\ArticleCategory;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 文章服务类
 */
class ArticleService extends Service
{
    /**
     * 分类列表
     *
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author zero
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
     * @author zero
     */
    public static function lists(array $get): array
    {
        $where = [];
        if (!empty($get['cid'])) {
            $where[] = ['cid', '=', intval($get['cid'])];
        }

        self::setSearch([
            '%like%' => ['keyword@title']
        ]);

        $modelArticle = new Article();
        return $modelArticle->field(['id,image,title,intro,browse,create_time'])
            ->where($where)
            ->where(self::$searchWhere)
            ->where(['is_delete'=>0])
            ->where(['is_show'=>1])
            ->order('id desc, browse desc')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page'  => 'page'
            ])->toArray();
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
        $modelArticle = new Article();
        return $modelArticle
            ->field(['id,title,intro,content,browse,create_time'])
            ->where(['id'=>$id])
            ->where(['is_delete'=>0])
            ->findOrFail()
            ->toArray();
    }
}