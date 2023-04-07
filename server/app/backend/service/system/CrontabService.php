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

namespace app\backend\service\system;

use app\common\basics\Service;
use app\common\model\sys\SysCrontab;
use JetBrains\PhpStorm\ArrayShape;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 系统计划任务服务类
 */
class CrontabService extends Service
{
    /**
     * 计划任务列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author zero
     */
    #[ArrayShape(['count' => "mixed", 'list' => "mixed"])]
    public static function lists(array $get): array
    {
        $model = new SysCrontab();
        $lists = $model
            ->withoutField('exe_time,remarks,create_time,update_time')
            ->order('id asc')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page'  => 'page'
            ])->toArray();

        return ['count'=>$lists['total'], 'list'=>$lists['data']];
    }

    /**
     * 计划任务详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function detail(int $id): array
    {
        $model = new SysCrontab();
        return $model->withoutField('create_time,update_time')
            ->where(['id'=>intval($id)])
            ->findOrFail()
            ->toArray();
    }

    /**
     * 计划任务新增
     *
     * @param array $post
     * @author zero
     */
    public static function add(array $post): void
    {
        SysCrontab::create([
            'name'    => $post['name'],
            'command' => $post['command'],
            'params'  => $post['params']  ?? '',
            'rules'   => $post['rules']   ?? '',
            'remarks' => $post['remarks'] ?? '',
            'status'  => $post['status']
        ]);
    }

    /**
     * 计划任务编辑
     *
     * @param array $post
     * @author zero
     */
    public static function edit(array $post): void
    {
        SysCrontab::update([
            'name'    => $post['name'],
            'command' => $post['command'],
            'params'  => $post['params']  ?? '',
            'rules'   => $post['rules']   ?? '',
            'remarks' => $post['remarks'] ?? '',
            'status'  => $post['status']
        ], ['id'=>intval($post['id'])]);
    }

    /**
     * 计划任务删除
     *
     * @param int $id
     * @author zero
     */
    public static function del(int $id): void
    {
        SysCrontab::destroy(intval($id));
    }

    /**
     * 计划任务停止
     *
     * @param int $id
     * @author zero
     */
    public static function stop(int $id): void
    {
        SysCrontab::update([
            'status'      => 2,
            'update_time' => time()
        ], ['id'=>intval($id)]);
    }

    /**
     * 计划任务运行
     *
     * @param int $id
     * @author zero
     */
    public static function run(int $id): void
    {
        SysCrontab::update([
            'status'      => 1,
            'error'       => '',
            'exe_time'    => 0,
            'max_time'    => 0,
            'update_time' => time()
        ], ['id'=>intval($id)]);
    }
}