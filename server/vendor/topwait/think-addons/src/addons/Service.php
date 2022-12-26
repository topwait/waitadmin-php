<?php
declare(strict_types=1);

namespace think\addons;

use think\Console;
use think\Route;
use think\facade\Config;
use think\facade\Lang;
use think\facade\Cache;
use think\facade\Event;
use think\addons\middleware\Addons;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Service extends \think\Service
{
    /**
     * 插件路径
     * @var string
     */
    protected $addonsPath;

    /**
     * 注册服务
     */
    public function register()
    {
        // 获取插件路径
        $this->addonsPath = $this->getAddonsPath();
        // 加载插件语言
        $this->loadLang();
        // 自动载入插件
        $this->autoload();
        // 加载插件事件
        $this->loadEvent();
        // 加载插件服务
        $this->loadService();
        // 挂载插件路由
        $this->loadRoutes();
        // 加载插件配置
        $this->loadApp();
        // 绑定插件容器
        $this->app->bind('addons', Service::class);
    }

    /**
     * 安装服务
     */
    public function boot()
    {
        $this->registerRoutes(function (Route $route) {
            // 注册控制器路由
            $execute = '\\think\\addons\\Route::execute';
            $traffic = 'addons/:addon/[:module]/[:controller]/[:action]';
            $route->rule($traffic, $execute)->middleware(Addons::class);

            // 注册自定义路由
            $routes = (array) Config::get('addons.route', []);
            if (Config::get('addons.autoload', true)) {
                foreach ($routes as $key => $val) {
                    if (!$val) {
                        continue;
                    }

                    if (is_array($val)) {
                        if (isset($val['rule']) && isset($val['domain'])) {
                            $domain = $val['domain'];
                            $rules = [];
                            foreach ($val['rule'] as $k => $rule) {
                                $rule = rtrim($rule, '/');
                                [$addon, $module, $controller, $action] = explode('/', $rule);
                                $rules[$k] = [
                                    'addon'      => $addon,
                                    'module'     => $module,
                                    'controller' => $controller,
                                    'action'     => $action,
                                    'indomain'   => 1,
                                ];
                            }
                            if($domain){
                                if (!$rules) $rules = [
                                    '/' => [
                                        'addon'      => $val['addons'],
                                        'module'     => 'frontend',
                                        'controller' => 'index',
                                        'action'     => 'index',
                                    ]
                                ];
                                foreach (explode(',', $domain) as $item) {
                                    $route->domain($item, function () use ($rules, $route, $execute) {
                                        foreach ($rules as $k => $rule) {
                                            $k = explode('/', trim($k,'/'));
                                            $k = implode('/', $k);
                                            $route->rule($k, $execute)
                                                ->completeMatch(true)
                                                ->append($rule);
                                        }
                                    });
                                }
                            }else{
                                foreach ($rules as $k => $rule) {
                                    $k = '/' . trim($k,'/');
                                    $route->rule($k, $execute)
                                        ->completeMatch(true)
                                        ->append($rule);
                                }
                            }
                        }
                    } else {
                        $val = rtrim($val, '/');
                        list($addon, $module, $controller, $action) = explode('/', $val);
                        $route->rule($key, $execute)
                            ->completeMatch(true)
                            ->append([
                                'addon'      => $addon,
                                'module'     => $module,
                                'controller' => $controller,
                                'action'     => $action
                            ]);
                    }
                }
            }
        });
    }

    /**
     * 自动加载
     */
    private function autoload(): bool
    {
        // 钩子是否自动载入
        if (!Config::get('addons.autoload', true)) {
            return true;
        }

        // 插件钩子写入配置
        $config = Config::get('addons');
        $base = get_class_methods("\\think\\Addons");
        $base = array_merge($base, ['init', 'initialize', 'install', 'uninstall', 'enabled', 'disabled']);
        foreach (glob($this->getAddonsPath() . '*/*.php') as $addonsFile) {
            $info = pathinfo($addonsFile);
            $name = pathinfo($info['dirname'], PATHINFO_FILENAME);
            if (strtolower($info['filename']) === 'plugin') {
                $methods = (array)get_class_methods("\\addons\\" . $name . "\\" . $info['filename']);
                $hooks = array_diff($methods, $base);
                foreach ($hooks as $hook) {
                    if (!isset($config['hooks'][$hook])) {
                        $config['hooks'][$hook] = [];
                    }
                    if (is_string($config['hooks'][$hook])) {
                        $config['hooks'][$hook] = explode(',', $config['hooks'][$hook]);
                    }
                    if (!in_array($name, $config['hooks'][$hook])) {
                        $config['hooks'][$hook][] = $name;
                    }
                }
            }
        }

        Config::set($config, 'addons');
        return true;
    }

    /**
     * 加载语言
     */
    private function loadLang(): void
    {
        Lang::load([$this->app->getRootPath() . '/vendor/topwait/think-addons/src/lang/zh-cn.php']);
    }

    /**
     * 加载事件
     */
    private function loadEvent(): void
    {
        // 初始化钩子
        $hooks = $this->app->isDebug() ? [] : Cache::get('hooks', []);
        if (empty($hooks)) {
            $hooks = (array) Config::get('addons.hooks', []);
            foreach ($hooks as $key => $values) {
                if (is_string($values)) {
                    $values = explode(',', $values);
                } else {
                    $values = (array)$values;
                }
                $hooks[$key] = array_filter(array_map(function ($v) use ($key) {
                    return [get_addons_class($v), $key];
                }, $values));
            }
            Cache::set('hooks', $hooks);
        }

        // 直接执行钩子
        if (isset($hooks['AddonsInit'])) {
            foreach ($hooks['AddonsInit'] as $k => $v) {
                Event::trigger('AddonsInit', $v);
            }
        }

        // 监听钩子事件
        Event::listenEvents($hooks);
    }

    /**
     * 加载服务
     */
    private function loadService(): void
    {
        $results = scandir($this->addonsPath);
        $bind = [];
        foreach ($results as $name) {
            if (in_array($name, ['.', '..'])) {
                continue;
            }

            if (is_file($this->addonsPath . $name)) {
                continue;
            }

            $addonDir = $this->addonsPath . $name . DS;
            if (!is_dir($addonDir)) {
                continue;
            }

            if (!is_file($addonDir . ucfirst($name) . '.php')) {
                continue;
            }

            $serviceFile = $addonDir . 'service.ini';
            if (!is_file($serviceFile)) {
                continue;
            }

            $info = parse_ini_file($serviceFile, true, INI_SCANNER_TYPED) ?: [];
            $bind = array_merge($bind, $info);
        }
        $this->app->bind($bind);
    }

    /**
     * 加载路由
     */
    private function loadRoutes(): void
    {
        foreach (scandir($this->addonsPath) as $addonName) {
            if (in_array($addonName, ['.', '..'])) {
                continue;
            }

            if (!is_dir($this->addonsPath . $addonName)) {
                continue;
            }

            $moduleDir = $this->addonsPath . $addonName . DS;
            foreach (scandir($moduleDir) as $mdir) {
                if (in_array($mdir, ['.', '..'])) {
                    continue;
                }

                if(is_file($this->addonsPath . $addonName . DS . $mdir)) {
                    continue;
                }

                $addonRouteFile = $this->addonsPath . $addonName . DS . $mdir . DS . 'route.php';
                $addonsRouteDir = $this->addonsPath . $addonName . DS . $mdir . DS . 'route' . DS;
                if (file_exists($addonsRouteDir) && is_dir($addonsRouteDir)) {
                    $files = glob($addonsRouteDir . '*.php');
                    foreach ($files as $file) {
                        if (file_exists($file)) {
                            $this->loadRoutesFrom($file);;
                        }
                    }
                } elseif (file_exists($addonRouteFile) && is_file($addonRouteFile)) {
                    $this->loadRoutesFrom($addonRouteFile);;
                }
            }
        }
    }

    /**
     * 加载配置
     */
    private function loadApp()
    {
        $app   = app();
        $rules = explode('/', ltrim($app->request->url(), '/'));
        $addon  = $rules[0] ?? '';
        $module = $rules[1] ?? '';
        if ($addon !== 'addons') {
            if (($rules[1]??'') === 'addons') {
                $addon  = $rules[1] ?? '';
                $module = $rules[2] ?? '';
            }
        }

        if ($addon !== 'addons' || !$module) {
            $routes = (array) Config::get('addons.route', []);
            $domain = explode('.', httpDomain())[0];
            foreach ($routes as $key => $val) {
                if (!$val) { continue; }
                if (is_array($val) && trim($val['domain']) === $domain) {
                    $addon  = trim($val['addons']);
                    $module = trim($val['module']);
                }
            }
        }

        // 加载插件应用级配置
        if (is_dir($this->addonsPath)) {
            foreach (scandir($this->addonsPath) as $name) {
                if (in_array($name, ['.', '..', 'public', 'view', 'middleware'])) {
                    continue;
                }

                $appConfigs = ['common.php', 'middleware.php', 'provider.php', 'event.php'];
                if (in_array($name, $appConfigs)) {
                    if (is_file($this->addonsPath . DS . 'common.php')) {
                        include_once $this->addonsPath . DS . 'common.php';
                    }

                    if (is_file($this->addonsPath . DS . 'middleware.php')) {
                        $app->middleware->import(include $this->addonsPath . DS . 'middleware.php', 'route');
                    }

                    if (is_file($this->addonsPath . DS . 'provider.php')) {
                        $app->bind(include $this->addonsPath . DS . 'provider.php');
                    }

                    if (is_file($this->addonsPath . DS . 'event.php')) {
                        $app->loadEvent(include $this->addonsPath . DS . 'event.php');
                    }

                    $commands = [];
                    $addonsConfigDir = $this->addonsPath . DS . 'config' . DS;
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

                    $addonsLangDir = $this->addonsPath . DS . 'lang' . DS;
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
        if (is_dir($this->addonsPath . $module)) {
            foreach (scandir($this->addonsPath . $module) as $modName) {
                if (in_array($modName, ['.', '..', 'public', 'view'])) {
                    continue;
                }

                $moduleConfigs = ['common.php', 'middleware.php', 'provider.php', 'event.php', 'config'];
                if (in_array($modName, $moduleConfigs)) {
                    if (is_file($this->addonsPath . $module . DS . 'common.php')) {
                        include_once $this->addonsPath . $module . DS . 'common.php';
                    }

                    if (is_file($this->addonsPath . $module . DS . 'middleware.php')) {
                        $app->middleware->import(include $this->addonsPath . $module . DS . 'middleware.php', 'route');
                    }

                    if (is_file($this->addonsPath . $module . DS . 'provider.php')) {
                        $app->bind(include $this->addonsPath . $module . DS . 'provider.php');
                    }

                    if (is_file($this->addonsPath . $module . DS . 'event.php')) {
                        $app->loadEvent(include $this->addonsPath . $module . DS . 'event.php');
                    }

                    $commands = [];
                    $moduleConfigDir = $this->addonsPath . $module . DS . 'config' . DS;
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

                    $addonsLangDir = $this->addonsPath . $module . DS . 'lang' . DS;
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

    /**
     * 拷贝目录
     *
     * @param string $source (原始目录路径)
     * @param string $target (目标目录路径)
     * @author windy
     */
    private static function copyDir(string $source, string $target)
    {
        if (!is_dir($target)) {
            mkdir($target, 0755, true);
        }

        foreach (
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            ) as $item
        ) {
            if ($item->isDir()) {
                $sontDir = $target . $iterator->getSubPathName();
                if (!is_dir($sontDir)) {
                    mkdir($sontDir, 0755, true);
                }
            } else {
                $to = rtrim(rtrim($target, '\\'), '/');
                copy($item->getPathName(), $to . DS . $iterator->getSubPathName());
            }
        }
    }

    /**
     * 删除目录
     *
     * @param string $dir (目录路径)
     * @return bool
     * @author windy
     */
    private static function deleteDir(string $dir): bool
    {
        // 验证是否目录
        if(!is_dir($dir)) return true;

        // 递归删除文件
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                if(!is_dir($fullpath)) {
                    @unlink($fullpath);
                } else {
                    self::deleteDir($fullpath);
                }
            }
        }
        closedir($dh);

        // 删除当前目录
        if(@rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 移除空目录
     *
     * @param string $dir (目录路径)
     * @author windy
     */
    private static function removeEmptyDir(string $dir)
    {
        try {
            $isDirEmpty = !(new \FilesystemIterator($dir))->valid();
            if ($isDirEmpty) {
                @rmdir($dir);
                self::removeEmptyDir(dirname($dir));
            }
        } catch (\UnexpectedValueException $e) {

        } catch (\Exception $e) {

        }
    }

    /**
     * 获设插件RC
     *
     * @param string $name   (插件名称)
     * @param array $changed (变动后数据)
     * @return array
     * @author windy
     */
    private static function addonrc(string $name, array $changed = []): array
    {
        $addonConfigFile = self::getAddonsDirs($name) . '.addonrc';

        $config = [];
        if (is_file($addonConfigFile)) {
            $config = (array)json_decode(file_get_contents($addonConfigFile), true);
        }

        $config = array_merge($config, $changed);
        if ($changed) {
            file_put_contents($addonConfigFile, json_encode($config, JSON_UNESCAPED_UNICODE));
        }

        return $config;
    }

    /**
     * 获取插件路径
     *
     * @return string
     * @author windy
     */
    public function getAddonsPath(): string
    {
        $addonsPath = $this->app->getRootPath() . 'addons' . DS;
        if (!is_dir($addonsPath)) {
            @mkdir($addonsPath, 0755, true);
        }
        return $addonsPath;
    }

    /**
     * 获取插件目录
     *
     * @param string $name
     * @return string
     * @author windy
     */
    public static function getAddonsDirs(string $name)
    {
        return app()->getRootPath() . 'addons' . DS . $name . DS;
    }

    /**
     * 取插件全局文件
     *
     * @param string $name (插件名称)
     * @param bool $onlyConflict (是否只返回冲突文件)
     * @return array
     * @author windy
     */
    public static function getGlobalAddonsFiles(string $name, bool $onlyConflict = false): array
    {
        $list = [];
        $addonDir = app()->getRootPath() . 'addons' . DS . $name . DS;
        $assetDir = get_target_assets_dir($name);

        // 扫描插件目录是否有覆盖的文件
        foreach (['app', 'public'] as $k => $dirName) {
            // 检测目录是否存在
            $addonPublicPath = $addonDir . $dirName . DS;
            if (!is_dir($addonPublicPath)) {
                continue;
            }

            // 检测不存在则创建
            if (!is_dir($assetDir)) {
                mkdir($assetDir, 0755, true);
            }

            // 匹配出所有的文件
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($addonPublicPath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $fileInfo) {
                if ($fileInfo->isFile()) {
                    // 获取出插件对应目录路径
                    $filePath = $fileInfo->getPathName();

                    // 处理插件基本的目录路径
                    $path = str_replace($addonDir, '', $filePath);

                    // 对插件静态文件特殊处理
                    if ($dirName === 'public') {
                        $path = str_replace(root_path(), '', $assetDir) . str_replace($addonDir . $dirName . DS, '', $filePath);
                    }

                    if ($onlyConflict) {
                        // 与插件原文件有冲突
                        $destPath = root_path() . $path;
                        if (is_file($destPath)) {
                            if (filesize($filePath) != filesize($destPath) || md5_file($filePath) != md5_file($destPath)) {
                                $list[] = $path;
                            }
                        }
                    } else {
                        // 与插件原文件无冲突
                        $list[] = $path;
                    }
                }
            }
        }

        return array_filter(array_unique($list));
    }


    /**
     * 安装插件应用
     *
     * @param string $name
     * @param false $isDelete
     * @author windy
     */
    public static function installAddonsApp(string $name, $isDelete = false): void
    {
        // 刷新插件配置缓存
        $files = self::getGlobalAddonsFiles($name);
        if ($files) {
            self::addonrc($name, ['files' => $files]);
        }

        // 复制应用到全局位
        foreach (['app', 'public'] as $k => $dir) {
            $sourceDir = self::getAddonsDirs($name) . $dir;
            $targetDir = app()->getBasePath();

            if ($dir === 'public') {
                $targetDir = app()->getRootPath() . $dir . DS . 'static' . DS . 'addons' . DS . $name . DS;
            }

            if (is_dir($sourceDir)) {
                self::copyDir($sourceDir, $targetDir);
                if ($isDelete) {
                    self::deleteDir(self::getAddonsDirs($name) . $dir);
                }
            }
        }
    }

    /**
     * 卸载插件应用
     *
     * @param string $name
     * @author windy
     */
    public static function uninstallAddonsApp(string $name): void
    {
        $addonRc  = self::addonrc($name);
        $addonDir = self::getAddonsDirs($name);
        $filesArr = self::getGlobalAddonsFiles($name);
        $targetAssetsDir = get_target_assets_dir($name);

        // 把散布在全局的文件复制回插件目录
        if ($addonRc && isset($addonRc['files']) && is_array($addonRc['files'])) {
            foreach ($addonRc['files'] as $index => $item) {
                // 避免不同服务器路径不一样
                $item = str_replace(['/', '\\'], DS, $item);
                $path = root_path() . $item;

                // 针对静态资源的特殊的处理
                if (stripos($item, str_replace(root_path(), '', $targetAssetsDir)) === 0) {
                    $baseAssert = str_replace(root_path(), '', $targetAssetsDir);
                    $item = 'public' . DS . str_replace($baseAssert, '', $item);
                }

                // 检查插件目录不存在则创建
                $itemBaseDir = dirname($addonDir . $item);
                if (!is_dir($itemBaseDir)) {
                    @mkdir($itemBaseDir, 0755, true);
                }

                // 检查如果是文件则移动位置
                if (is_file($path)) {
                    @copy($path, $addonDir.$item);
                }
            }
            $filesArr = $addonRc['files'];
        }

        // 移除插件的文件
        $dirs = [];
        foreach ($filesArr as $path) {
            $file = root_path() . $path;
            $dirs[] = dirname($file);
            @unlink($file);
        }

        // 移除插件空目录
        $dirs = array_filter(array_unique($dirs));
        foreach ($dirs as $path) {
            self::removeEmptyDir($path);
        }
    }
}
