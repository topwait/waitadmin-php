<?php
declare(strict_types=1);

namespace think\addons;

use think\Console;
use think\facade\Lang;
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
    public static function execute($module = 'frontend')
    {
        $app = app();
        $request    = $app->request;
        $addon      = $request->route('addon');
        $controller = $request->route('controller');
        $action     = $request->route('action');

        // 适配单层应用
        $addonsPath = app()->getRootPath() . 'addons' . DS;
        $AddonCheck = strpos($request->pathinfo(), 'addons') === 0 ? true : false;
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
        $request->setController("{$module}.{$controller}")->setAction($action);

        // 验证是否可用
        $info = get_addons_info($addon);
        if (!$info || !$info['status']) {
            $message = !$info ? [404, 'addon %s not found'] : [500, 'addon %s is disabled'];
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

    /**
     * 加载配置
     */
    private static function loadApp()
    {
        $addonsPath = app()->getRootPath() . 'addons' . DS;
        $app   = app();
        $rules = explode('/', $app->request->url());
        $rules = array_splice($rules, 2, count($rules)-1);
        $addon  = $app->request->route('addon')  ?? '';
        $module = $app->request->route('module') ?? '';

        // 加载插件应用级配置
        if (is_dir($addonsPath . $addon)) {
            foreach (scandir($addonsPath . $addon) as $name) {
                if (in_array($name, ['.', '..', 'public', 'view', 'middleware'])) {
                    continue;
                }

                $appConfigs = ['common.php', 'middleware.php', 'provider.php', 'event.php'];
                if (in_array($name, $appConfigs)) {
                    if (is_file($addonsPath . $addon . DS . 'common.php')) {
                        include_once $addonsPath . $addon . DS . 'common.php';
                    }

                    if (is_file($addonsPath . $addon . DS . 'middleware.php')) {
                        $app->middleware->import(include $addonsPath . $addon . DS . 'middleware.php', 'route');
                    }

                    if (is_file($addonsPath . $addon . DS . 'provider.php')) {
                        $app->bind(include $addonsPath . $addon . DS . 'provider.php');
                    }

                    if (is_file($addonsPath . $addon . DS . 'event.php')) {
                        $app->loadEvent(include $addonsPath . $addon . DS . 'event.php');
                    }

                    $commands = [];
                    $addonsConfigDir = $addonsPath . $addon . DS . 'config' . DS;
                    if (is_dir($addonsConfigDir)) {
                        $files = [];
                        $files = array_merge($files, glob($addonsConfigDir . '*' . $app->getConfigExt()));
                        if ($files) {
                            foreach ($files as $file) {
                                if (file_exists($file)) {
                                    if (substr($file, -11) == 'console.php') {
                                        $commandsConfig = include_once $file;
                                        isset($commandsConfig['commands']) && $commands = array_merge($commands, $commandsConfig['commands']);
                                        !empty($commands) && Console::starting(function (Console $console) use ($commands) {
                                            $console->addCommands($commands);
                                        });
                                    } else {
                                        $app->config->load($file, pathinfo($file, PATHINFO_FILENAME));
                                    }
                                }
                            }
                        }
                    }

                    $addonsLangDir = $addonsPath . $addon . DS . 'lang' . DS;
                    if (is_dir($addonsLangDir)) {
                        $files = glob($addonsLangDir . $app->lang->defaultLangSet() . '.php');
                        foreach ($files as $file) {
                            if (file_exists($file)) {
                                Lang::load([$file]);
                            }
                        }
                    }
                }
            }
        }

        // 加载插件模块级配置
        if (is_dir($addonsPath . $addon . DS . $module)) {
            foreach (scandir($addonsPath . $addon . DS . $module) as $modName) {
                if (in_array($modName, ['.', '..', 'public', 'view'])) {
                    continue;
                }

                $moduleConfigs = ['common.php', 'middleware.php', 'provider.php', 'event.php', 'config'];
                if (in_array($modName, $moduleConfigs)) {
                    if (is_file($addonsPath . $addon . DS . $module . DS . 'common.php')) {
                        include_once $addonsPath . $addon . DS . $module . DS . 'common.php';
                    }

                    if (is_file($addonsPath . $addon . DS . $module . DS . 'middleware.php')) {
                        $app->middleware->import(include $addonsPath . $addon . DS . $module . DS . 'middleware.php', 'route');
                    }

                    if (is_file($addonsPath . $addon . DS . $module . DS . 'provider.php')) {
                        $app->bind(include $addonsPath . $addon . DS . $module . DS . 'provider.php');
                    }

                    if (is_file($addonsPath . $addon . DS . $module . DS . 'event.php')) {
                        $app->loadEvent(include $addonsPath . $addon . DS . $module . DS . 'event.php');
                    }

                    $commands = [];
                    $moduleConfigDir = $addonsPath . $addon . DS . $module . DS . 'config' . DS;
                    if (is_dir($moduleConfigDir)) {
                        $files = [];
                        $files = array_merge($files, glob($moduleConfigDir . '*' . $app->getConfigExt()));
                        if($files){
                            foreach ($files as $file) {
                                if (file_exists($file)) {
                                    if (substr($file,-11) != 'console.php') {
                                        $app->config->load($file, pathinfo($file, PATHINFO_FILENAME));
                                    } else {
                                        $commandsConfig = include_once $file;
                                        isset($commandsConfig['commands']) && $commands = array_merge($commands, $commandsConfig['commands']);
                                        !empty($commands) && Console::starting(function (Console $console) use($commands) {$console->addCommands($commands);});
                                    }
                                }
                            }
                        }
                    }

                    $addonsLangDir = $addonsPath . $addon . DS . $module . DS . 'lang' . DS;
                    if (is_dir($addonsLangDir)) {
                        $files = glob($addonsLangDir . $app->lang->defaultLangSet() . '.php');
                        foreach ($files as $file) {
                            if (file_exists($file)) {
                                Lang::load([$file]);
                            }
                        }
                    }
                }
            }
        }
    }
}
