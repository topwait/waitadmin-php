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
use app\common\model\auth\AuthDept;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 部门服务类
 */
class DeptService extends Service
{
    /**
     * 部门列表
     *
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function lists(): array
    {
        $modelAuthDept = new AuthDept();
        $lists = $modelAuthDept
            ->withoutField('is_delete,update_time,delete_time')
            ->where(self::$searchWhere)
            ->where(['is_delete'=>0])
            ->order('sort desc, id desc')
            ->select()
            ->toArray();

        foreach ($lists as &$item) {
            $item['duty']   = $item['duty'] ?: '-';
            $item['mobile'] = $item['mobile'] ?: '-';
        }

        return $lists;
    }

    /**
     * 部门详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function detail(int $id): array
    {
        $modelAuthDept = new AuthDept();
        return $modelAuthDept
            ->withoutField('is_delete,update_time,delete_time')
            ->where(['id'=> $id])
            ->where(['is_delete'=>0])
            ->findOrFail()
            ->toArray();
    }

    /**
     * 部门新增
     *
     * @param array $post
     * @throws OperateException
     * @author zero
     */
    public static function add(array $post): void
    {
        // 验证唯一
        $pid = intval($post['pid']);
        $modelAuthDept = new AuthDept();
        if ($pid === 0) {
            $modelAuthDept->checkDataAlreadyExist(['is_delete'=>0], '只允许存在一个顶级部门!');
        }

        // 验证父级
        if ($pid > 0) {
            $modelAuthDept->checkDataDoesNotExist(['id'=>$pid, 'is_delete'=>0], '父级菜单已不存在!');
        }

        // 创建部门
        $authDept = AuthDept::create([
            'pid'         => $post['pid'],
            'name'        => $post['name'],
            'duty'        => $post['duty'],
            'mobile'      => $post['mobile'],
            'sort'        => empty($post['sort']) ? 0 : $post['sort'],
            'level'       => 1,
            'relation'    => '',
            'is_disable'  => $post['is_disable'],
            'is_delete'   => 0,
            'create_time' => time(),
            'update_time' => time()
        ]);

        // 更新关系
        if ($pid === 1) {
            AuthDept::update([
                'level'    => 1,
                'relation' => '0,' . $authDept['id']
            ], ['id'=>$authDept['id']]);
        } else if ($pid > 1) {
            $parentDept = $modelAuthDept->field('id,pid,level,relation')->findOrEmpty($pid)->toArray();
            AuthDept::update([
               'level'    => $parentDept['level'] + 1,
               'relation' => $parentDept['relation'] . ',' . $authDept['id']
            ], ['id'=>$authDept['id']]);
        }
    }

    /**
     * 部门编辑
     *
     * @param array $post
     * @throws OperateException
     * @author zero
     */
    public static function edit(array $post): void
    {
        // 接收参数
        $id  = intval($post['id']);
        $pid = intval($post['pid']);

        // 验证数据
        $modelAuthDept = new AuthDept();
        $modelAuthDept->checkDataDoesNotExist(['id'=>$id, 'is_delete'=>0]);

        // 验证父级
        if ($pid > 1) {
            $modelAuthDept->checkDataDoesNotExist(['id'=>$pid, 'is_delete'=>0], '父级菜单已不存在!');
            if (in_array($pid, self::child($id))) {
                throw new OperateException('父级不允许是自身或子级!');
            }
        }

        // 更新部门
        AuthDept::update([
            'pid'         => $pid,
            'name'        => $post['name'],
            'duty'        => $post['duty'],
            'mobile'      => $post['mobile'],
            'sort'        => empty($post['sort']) ? 0 : $post['sort'],
            'is_disable'  => $post['is_disable'],
            'update_time' => time()
        ], ['id'=>intval($post['id'])]);

        // 更新状态
        AuthDept::update(['is_disable'=>$post['is_disable']], [['id', 'in', self::child($id)]]);

        // 当前部门
        $authDept = $modelAuthDept->field('id,pid,name,level,relation')
            ->where(['id'=>intval($post['id'])])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 父级部门
        $parentDept = $modelAuthDept->field('id,pid,name,level,relation')
            ->where(['id'=>$pid])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        // 处理关系
        $relation = $authDept['relation'];
        if ($pid === 0) {
            $replaceLevel = $authDept['level'] - 1;
            $replacePaths = '0,'.$authDept['id'];
        } else {
            $replaceLevel = $authDept['level'] - ($parentDept['level'] + 1);
            $replacePaths = $parentDept['relation'] . ',' . $authDept['id'];
        }

        // 更新关系
        $modelAuthDept->where("find_in_set($id,relation)")
            ->exp('level', "level - $replaceLevel")
            ->exp('relation', "replace(relation, '$relation', '$replacePaths')")
            ->update([]);
    }

    /**
     * 部门删除
     *
     * @param int $id
     * @throws OperateException
     * @author zero
     */
    public static function del(int $id): void
    {
        $modelAuthDept = new AuthDept();
        $modelAuthDept->checkDataDoesNotExist(['id'=>$id, 'is_delete'=>0]);
        $modelAuthDept->checkDataAlreadyExist(['pid'=>$id, 'is_delete'=>0], '请先删除子部门再操作!');

        $modelAdmin = new AuthAdmin();
        $modelAdmin->checkDataAlreadyExist(['dept_id'=>$id, 'is_delete'=>0], '部门已被管理员使用!');

        AuthDept::update([
            'is_delete'   => 1,
            'delete_time' => time()
        ], ['id'=>$id]);
    }

    /**
     * 部门子级ID
     *
     * @param int $id
     * @return array
     * @author zero
     */
    public static function child(int $id): array
    {
        $modelAuthDept = new AuthDept();
        return $modelAuthDept
            ->whereRaw("FIND_IN_SET($id, relation)")
            ->where(['is_delete'=>0])
            ->column('id');
    }
}