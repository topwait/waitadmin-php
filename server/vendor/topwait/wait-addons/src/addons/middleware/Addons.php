<?php
// +----------------------------------------------------------------------
// | 基于ThinkPHP6的插件化模块 [WaitAdmin专属订造]
// +----------------------------------------------------------------------
// | github: https://github.com/topwait/wait-addons
// | Author: zero <2474369941@qq.com>
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace wait\addons\middleware;

use Closure;
use think\App;

class Addons
{
    /**
     * app对象
     * @var App
     */
    protected App $app;

    /**
     * 构造函数
     *
     * Addons constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * 插件中间件
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     * @author zero
     */
    public function handle($request, Closure $next): mixed
    {
        hook('addon_middleware', $request);
        return $next($request);
    }
}