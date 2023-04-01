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

namespace app\frontend\service;

use app\common\basics\Service;
use app\common\model\article\Article;
use app\common\model\article\ArticleCategory;
use app\common\model\article\ArticleCollect;
use Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 文章服务类
 */
class ArticleService extends Service
{
    /**
     * 获取类目
     *
     * @param int $cid
     * @return mixed
     * @author windy
     */
    public static function category(int $cid): mixed
    {
        $model = new ArticleCategory();
        $name = $model->where(['id'=> $cid])->value('name');
        return $name ?: '文章列表';
    }

    /**
     * 文章列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author windy
     */
    public static function lists(array $get): array
    {
        $where = [];
        if (!empty($get['cid']) && intval($get['cid']) > 0) {
            $where[] = ['cid', 'in', intval($get['cid'])];
        }

        $model = new Article();
        $lists = $model->field('id,cid,title,image,intro,browse,create_time')
            ->with(['category'])
            ->where(['is_show'=>1])
            ->where(['is_delete'=>0])
            ->where($where)
            ->order('sort desc, id desc')
            ->paginate([
                'page'      => $get['page'] ?? 1,
                'list_rows' => 10,
                'var_page'  => 'page'
            ])->toArray();

        foreach ($lists['data'] as &$item) {
            $item['category'] = $item['category']['name'] ?? '无';
            $item['datetime'] = date('Y-m-d H:i:s', strtotime($item['create_time']));
            unset($item['cid']);
            unset($item['create_time']);
        }

        return $lists;
    }

    /**
     * 文章特色
     *
     * @param string $type
     * @param int $limit
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    public static function recommend(string $type, int $limit = 10): array
    {
        $order = 'sort desc, id desc';
        $where = [];
        switch ($type) {
            case 'everyday':
                $where[] = ['is_recommend', '=', 1];
                break;
            case 'topping':
                $where[] = ['is_topping', '=', 1];
                break;
            case 'lately':
                $order = 'update_time desc, id desc';
                break;
            case 'ranking':
                $order = 'browse desc, collect desc, id desc';
                break;
        }

        $model = new Article();
        $lists = $model->field('id,cid,title,image,intro,browse,create_time')
            ->with(['category'])
            ->where(['is_show'=>1])
            ->where(['is_delete'=>0])
            ->where($where)
            ->order($order)
            ->limit($limit)
            ->select()
            ->toArray();

        foreach ($lists as &$item) {
            $item['category'] = $item['category']['name'] ?? '无';
            $item['datetime'] = date('Y-m-d H:i:s', strtotime($item['create_time']));
            unset($item['cid']);
            unset($item['create_time']);
        }

        return $lists;
    }

    /**
     * 文章详情
     *
     * @param int $id
     * @param int $userId
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author windy
     */
    public static function detail(int $id, int $userId): array
    {
        $model = new Article();
        $detail = $model->field('id,cid,title,image,intro,content,browse,create_time')
            ->with(['category'])
            ->where(['id'=> $id])
            ->where(['is_delete'=>0])
            ->findOrFail()
            ->toArray();

        // 处理数据
        $detail['category'] = $detail['category']['name'] ?? '无';
        $detail['datetime'] = date('Y-m-d H:i', strtotime($detail['create_time']));
        unset($detail['cid']);
        unset($detail['create_time']);

        // 是否收藏
        $collect = (new ArticleCollect())->field('id')
            ->where(['user_id'=>$userId])
            ->where(['article_id'=>$detail['id']])
            ->where(['is_delete'=>0])
            ->findOrEmpty();
        $detail['collect'] = !$collect->isEmpty();

        // 上一条记录
        $detail['prev'] = $model->field('id,title')
            ->where('id', '<', intval($id))
            ->where('is_delete', '=', 0)
            ->order('sort desc, id desc')
            ->findOrEmpty()
            ->toArray();

        // 下一条记录
        $detail['next'] = $model->field('id,title')
            ->where('id', '>', intval($id))
            ->where('is_delete', '=', 0)
            ->order('sort desc, id desc')
            ->findOrEmpty()
            ->toArray();

        // 增加浏览
        Article::update([
            'browse'      => ['inc', 1],
            'update_time' => time(),
        ], ['id'=> $id]);

        return $detail;
    }

    /**
     * 文章收藏
     *
     * @param int $id
     * @param int $userId
     * @return string
     * @author windy
     */
    public static function collect(int $id, int $userId): string
    {
        $modelArticleCollect = new ArticleCollect();
        $collect = $modelArticleCollect->field('id,is_delete')
            ->where(['user_id'=> $userId])
            ->where(['article_id'=> $id])
            ->findOrEmpty()
            ->toArray();

        if (!$collect) {
            ArticleCollect::create([
                'type'        => 1,
                'user_id'     => $userId,
                'article_id'  => $id,
                'create_time' => time(),
                'update_time' => time()
            ]);

            Article::update([
                'collect' => ['inc', 1],
                'create_time' => time()
            ], ['id'=>$id]);

            return '收藏成功';
        } else {
            ArticleCollect::update([
                'is_delete'   => !$collect['is_delete'],
                'delete_time' => $collect['is_delete'] ? 0 : time(),
                'update_time' => time()
            ], ['id'=>$collect['id']]);

            try {
                Article::update([
                    'collect' => [$collect['is_delete'] ? 'inc' : 'dec', 1],
                    'create_time' => time()
                ], ['id' => $id]);
            } catch (Exception) {}

            return $collect['is_delete'] ? '收藏成功' : '收藏取消';
        }
    }
}