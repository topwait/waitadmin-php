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

namespace app\backend\service\user;

use app\common\basics\Service;
use app\common\model\sys\SysVisitor;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 用户访问日志服务类
 */
class VisitorService extends Service
{
    /**
     * 用户日志列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author zero
     */
    public static function lists(array $get): array
    {
        self::setSearch([
            '='        => ['method@v.method', 'status@v.status', 'url@v.url', 'ip@v.ip'],
            'like'     => ['username@u.account'],
            'datetime' => ['datetime@sl.create_time'],
        ]);

        $model = new SysVisitor();
        $lists = $model->field([
            'v.id,v.module,v.method,v.ip,v.browser,v.url',
            'v.status,v.task_time,v.create_time',
            'u.account'
        ])
            ->alias('v')
            ->order('v.id desc')
            ->where(self::$searchWhere)
            ->leftJoin('user u', 'u.id=v.user_id')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page'  => 'page'
            ])->toArray();

        foreach ($lists['data'] as &$item) {
            $item['account'] = $item['account'] ?: '-';
        }

        return ['count'=>$lists['total'], 'list'=>$lists['data']] ?? [];
    }

    /**
     * 用户日志详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function detail(int $id): array
    {
        $model = new SysVisitor();
        $detail = $model
            ->where(['id'=>$id])
            ->findOrFail()
            ->toArray();

        $endTime   = explode('.', $detail['end_time']);
        $startTime = explode('.', $detail['start_time']);
        $detail['end_time']   = date('Y-m-d H:i:s', intval($endTime[0])).'.'.$endTime[1];
        $detail['start_time'] = date('Y-m-d H:i:s', intval($startTime[0])).'.'.$startTime[1];
        $detail['status'] = $detail['status'] == 1 ? '成功' : '失败';
        return $detail;
    }
}