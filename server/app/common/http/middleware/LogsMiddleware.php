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

namespace app\common\http\middleware;

use app\common\model\sys\SysLog;
use app\common\model\sys\SysVisitor;
use Closure;

/**
 * 系统日志中间件
 */
class LogsMiddleware
{
    public function handle($request, Closure $next)
    {
        $startTime = round(app()->getBeginTime(), 3);
        $response  = $next($request);
        $endTime   = round(microtime(true), 3);
        $taskTime  = intval(($endTime - $startTime)*1000);

        $params = request()->param();
        if (isset($params['password'])) {
            $params['password'] = '******';
        }

        if (request()->isAjax() && app()->http->getName() === 'backend') {
            if (SysLog::$logId) {
                SysLog::update([
                    'task_time' => $taskTime,
                    'end_time'  => $endTime
                ], ['id'=>SysLog::$logId]);
            } else {
                $adminId  = session('adminUser')['id'] ?? 0;
                $entrance = config('project.backend_entrance');
                $baseUrls = str_replace($entrance, '', request()->baseUrl());
                SysLog::create([
                    'admin_id'    => $adminId,
                    'method'      => request()->method(),
                    'url'         => str_replace('.html', '', $baseUrls),
                    'ip'          => request()->ip(),
                    'ua'          => request()->header('User-Agent'),
                    'params'      => json_encode($params, JSON_UNESCAPED_UNICODE),
                    'task_time'   => $taskTime,
                    'start_time'  => $startTime,
                    'end_time'    => $endTime,
                    'create_time' => time()
                ]);
            }
        } else if (app()->http->getName() !== 'backend') {
            if (SysVisitor::$logId) {
                SysVisitor::update([
                    'task_time' => $taskTime,
                    'end_time'  => $endTime
                ], ['id'=>SysVisitor::$logId]);
            } else {
                $userId   = session('userId') ?? 0;
                $baseUrls = request()->baseUrl();
                SysVisitor::create([
                    'user_id'     => $userId,
                    'module'      => app()->http->getName(),
                    'method'      => request()->method(),
                    'url'         => str_replace('.html', '', $baseUrls),
                    'ip'          => request()->ip(),
                    'ua'          => request()->header('User-Agent'),
                    'browser'     => user_agent(),
                    'params'      => json_encode($params, JSON_UNESCAPED_UNICODE),
                    'task_time'   => $taskTime,
                    'start_time'  => $startTime,
                    'end_time'    => $endTime,
                    'create_time' => time()
                ]);
            }
        }

        return $response;
    }
}