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

use Closure;

/**
 * 自定义跨域中间件
 *
 * Class AllowCrossDomain
 * @package app\common\http\middleware
 */
class AllowCrossDomain
{
    /**
     * 跨域处理
     *
     * @author zero
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        $response = $next($request);
        $response->header([
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Headers'     => '*',
            'Access-Control-Allow-Methods'     => 'GET, POST',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '1728000',
        ]);
        if (strtoupper($request->method()) === 'OPTIONS') {
            $response->code(204);
        }
        return $response;
    }
}