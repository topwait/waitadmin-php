<?php
declare(strict_types=1);

namespace think\addons\middleware;

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
     */
    public function handle($request, Closure $next): mixed
    {
        hook('addon_middleware', $request);
        return $next($request);
    }
}