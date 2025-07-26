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

namespace app\backend\service\auth;

use app\common\basics\Service;
use app\common\exception\OperateException;
use app\common\model\auth\AuthAdmin;
use app\common\model\auth\AuthPost;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 岗位服务类
 */
class PostService extends Service
{
    /**
     * 所有岗位
     *
     * @return array
     * @author zero
     */
    public static function all(): array
    {
        $modelAuthPost = new AuthPost();
        return $modelAuthPost
            ->field('id,code,name')
            ->where(['is_delete'=>0])
            ->order('sort desc, id desc')
            ->select()->toArray();
    }

    /**
     * 岗位列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author zero
     */
    public static function lists(array $get): array
    {
        self::setSearch([
            '='      => ['code', 'status@is_disable'],
            '%like%' => ['name']
        ]);

        $modelAuthPost = new AuthPost();
        $lists = $modelAuthPost
            ->withoutField('is_delete,update_time,delete_time')
            ->where(self::$searchWhere)
            ->where(['is_delete'=>0])
            ->order('sort desc, id desc')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page'  => 'page'
            ])->toArray();

        foreach ($lists['data'] as &$item) {
            $item['remarks'] = $item['remarks'] ?: '-';
        }

        return ['count'=>$lists['total'], 'list'=>$lists['data']] ?? [];
    }

    /**
     * 岗位详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function detail(int $id): array
    {
        $modelAuthPost = new AuthPost();
        return $modelAuthPost
            ->withoutField('is_delete,update_time,delete_time')
            ->where(['id'=>$id])
            ->where(['is_delete'=>0])
            ->findOrFail()
            ->toArray();
    }

    /**
     * 岗位新增
     *
     * @param array $post
     * @author zero
     */
    public static function add(array $post): void
    {
        AuthPost::create([
            'code'        => $post['code'],
            'name'        => $post['name'],
            'remarks'     => $post['remarks'],
            'sort'        => empty($post['sort']) ? 0 : $post['sort'],
            'is_disable'  => $post['is_disable'],
            'is_delete'   => 0,
            'create_time' => time(),
            'update_time' => time()
        ]);
    }

    /**
     * 岗位编辑
     *
     * @param array $post
     * @throws OperateException
     * @author zero
     */
    public static function edit(array $post): void
    {
        $model = new AuthPost();
        $model->checkDataDoesNotExist(['id'=>intval($post['id']), 'is_delete'=>0]);

        AuthPost::update([
            'code'        => $post['code'],
            'name'        => $post['name'],
            'remarks'     => $post['remarks']??'',
            'sort'        => $post['sort']??0,
            'is_disable'  => $post['is_disable']??0,
            'update_time' => time()
        ], ['id'=>intval($post['id'])]);
    }

    /**
     * 岗位删除
     *
     * @param int $id
     * @throws OperateException
     * @author zero
     */
    public static function del(int $id): void
    {
        $modelAuthPost = new AuthPost();
        $modelAuthPost->checkDataDoesNotExist(['id'=>$id, 'is_delete'=>0]);

        $modelAdmin = new AuthAdmin();
        $modelAdmin->checkDataAlreadyExist(['post_id'=>$id, 'is_delete'=>0], '该部门已被管理员使用!');

        AuthPost::update([
            'is_delete'   => 1,
            'delete_time' => time()
        ], ['id'=>$id]);
    }
}