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

namespace app\backend\service\system;

use app\common\basics\Service;
use app\common\model\sys\SysLog;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 系统日志服务类
 */
class LogService extends Service
{
    /**
     * 系统日志列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author zero
     */
    public static function lists(array $get): array
    {
        self::setSearch([
            '='        => ['method@sl.method', 'status@sl.status', 'url@sl.url', 'ip@sl.ip'],
            '%like&'   => ['username@a.username'],
            'datetime' => ['datetime@sl.create_time'],
        ]);

        $model = new SysLog();
        $lists = $model->field([
                'sl.id,a.username,sl.method,sl.ip,sl.url',
                'sl.status,sl.task_time,sl.create_time'
            ])
            ->alias('sl')
            ->order('id desc')
            ->where(self::$searchWhere)
            ->leftJoin('auth_admin a', 'a.id=sl.admin_id')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page'  => 'page'
            ])->toArray();

        return ['count'=>$lists['total'], 'list'=>$lists['data']] ?? [];
    }

    /**
     * 系统日志详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function detail(int $id): array
    {
        $model = new SysLog();
        $detail = $model->field(true)
            ->where(['id'=>$id])
            ->findOrFail()
            ->toArray();


        $startTime = explode('.', $detail['start_time']);
        $endTime   = explode('.', $detail['end_time']);
        $detail['start_time'] = date('Y-m-d H:i:s', intval($startTime[0])).'.'.$startTime[1];
        $detail['end_time']   = date('Y-m-d H:i:s', intval($endTime[0])).'.'.$endTime[1];
        $detail['status'] = $detail['status'] == 1 ? '成功' : '失败';
        return $detail;
    }
}