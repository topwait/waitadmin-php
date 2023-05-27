<?php
// +----------------------------------------------------------------------
// | 基于ThinkPHP6的插件化模块 [WaitAdmin专属订造]
// +----------------------------------------------------------------------
// | github: https://github.com/topwait/wait-addons
// | Author: zero <2474369941@qq.com>
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace wait\addons;

use think\facade\Request;
use think\helper\Str;
use think\facade\Event;
use think\facade\Config;
use think\facade\View;
use think\exception\HttpException;

class Route
{
    /**
     * 插件路由请求
     *
     * @param string $module
     * @return mixed
     */
    public static function execute(string $module = 'frontend'): mixed
    {
        $app = app();
        $request    = $app->request;
        $addon      = $request->route('addon');
        $controller = $request->route('controller');
        $action     = $request->route('action');

        // 适配单层应用
        $addonsPath = app()->getRootPath() . 'addons' . DS;
        $AddonCheck = str_starts_with($request->pathinfo(), 'addons');
        $modulePath = $addonsPath . $addon . DS . ($module? ($module . DS):'');
        if (!is_dir($modulePath) || ($AddonCheck && !$action)) {
            $module = '';
            $action = $controller;
            $controller = $request->route('module');
        }

        // 监听前置事件
        Event::trigger('addons_begin', $request);

        // 验证插件路由
        if (empty($addon) || empty($controller) || empty($action)) {
            throw new HttpException(500, lang('addon can not be empty'));
        }

        // 设置请求操作
        $request->addon = $addon;
        $request->setController("$module.$controller")->setAction($action);

        // 验证是否可用
        $info = get_addons_info($addon);
        if (!$info || !$info['status'] || !$info['install']) {
            $message = (!$info||!$info['install']) ? [404, 'addon %s not found'] : [500, 'addon %s is disabled'];
            throw new HttpException($message[0], lang($message[1], [$addon]));
        }

        // 监听初始事件
        Event::trigger('addon_module_init', $request);

        // 控制器的后缀
        $newController = ucwords($controller);
        if (Config::get('route.controller_suffix')) {
            $controllerLayer = ucwords(Config::get('route.controller_layer'));
            $newController = $newController.$controllerLayer;
        }

        // 获取控制器类
        $class = get_addons_class($addon, 'controller', $newController, $module);
        if (!$class) {
            throw new HttpException(404, lang('addon controller %s not found', [Str::studly($module.DS.$newController)]));
        }

        // 重写视图路径
        $viewConfig = Config::get('view');
        if (!empty($viewConfig['method_suffix'])) {
            $ca = $controller.'.'.$action;
            foreach ($viewConfig['method_suffix'] as $key => $val) {
                if (in_array($ca, $val)) {
                    $viewConfig['view_suffix'] = $key;
                }
            }
        }
        $appViewPath = $app->addons->getAddonsPath() . $addon . DS . ($module? $module . DS : '') . 'view' . DS;
        $pubViewPath = $app->addons->getAddonsPath() . $addon . DS . 'view'. DS;
        $viewConfig['view_path'] = is_dir($appViewPath) ? $appViewPath : $pubViewPath;
        Config::set($viewConfig, 'view');
        View::engine('Think')->config($viewConfig);
        if (is_dir($appViewPath) && $module) {
            $viewController = $request->controller();
            $viewController = substr_replace($viewController, '', 0, strlen($module)+1);
            Request::setController($viewController);
        }

        // 操作方法的后缀
        $newAction = $action;
        if (Config::get('route.action_suffix')) {
            $newAction = $action.ucwords(Config::get('route.action_suffix'));
        }

        // 生成控制器对象
        $vars = [];
        $instance = new $class($app);
        if (is_callable([$instance, $newAction])) {
            $call = [$instance, $newAction];
        } elseif (is_callable([$instance, '_empty'])) {
            $call = [$instance, '_empty'];
            $vars = [$newAction];
        } else {
            throw new HttpException(404, lang('addon action %s not found', [get_class($instance).'->'.$newAction.'()']));
        }

        // 监听后置事件
        Event::trigger('addons_action_begin', $call);

        // 返回调用函数
        return call_user_func_array($call, $vars);
    }

}
