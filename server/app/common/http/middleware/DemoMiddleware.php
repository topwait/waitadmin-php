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

namespace app\common\http\middleware;

use app\common\exception\OperateException;
use Closure;

/**
 * 演示站点中间件
 */
class DemoMiddleware
{
    /**
     * 允许POST请求的接口
     * @var array
     */
    protected array $needPostApi = [
        'login/check',
        'login/logout'
    ];

    /**
     * 拦截处理
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws OperateException
     * @author zero
     */
    public function handle($request, Closure $next): mixed
    {
        if ($request->method() != 'POST' || !env('demo.demo_status')) {
            return $next($request);
        }

        $adminUser = session('adminUser')??[];
        $adminId = intval($adminUser['id']??0);
        if ($adminId === 1 and env('demo.demo_super')) {
            return $next($request);
        }

        $accessUri = strtolower($request->controller() . '/' . $request->action());
        if (!in_array($accessUri, $this->needPostApi)) {
            throw new OperateException('演示环境不支持修改数据, 请下载源码本地部署体验!');
        }

        return $next($request);
    }
}