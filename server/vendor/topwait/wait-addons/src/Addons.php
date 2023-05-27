<?php
// +----------------------------------------------------------------------
// | 基于ThinkPHP6的插件化模块 [WaitAdmin专属订造]
// +----------------------------------------------------------------------
// | github: https://github.com/topwait/wait-addons
// | Author: zero <2474369941@qq.com>
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace wait;

use Exception;
use think\App;
use think\facade\Config;
use think\facade\View;
use think\Request;

abstract class Addons
{
    // APP容器
    protected App $app;
    // 请求对象
    protected Request $request;
    // 插件路径
    protected string $addonPath;
    // 插件标识
    protected string $name;
    // 视图模型
    protected mixed $view;
    // 插件配置
    protected string $addonConfig;
    // 插件信息
    protected string $addonInfo;

    /**
     * 构造函数
     *
     * Addons constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $app->request;
        $this->name    = $this->getName();
        $this->addonPath   = $app->addons->getAddonsPath() . $this->name . DS;
        $this->addonConfig = "addon_{$this->name}_config";
        $this->addonInfo   = "addon_{$this->name}_info";
        $this->view = clone View::engine('Think');
        $this->view->config([
            'view_path' => $this->addonPath . 'view' . DS
        ]);

        // 控制器初始化
        $this->initialize();
    }

    /**
     * 初始化方法
     */
    protected function initialize()
    {}

    /**
     * 加载模板输出
     *
     * @param string $template 模板文件名
     * @param array $vars      模板输出变量
     * @return string          响应模板内容
     * @throws Exception
     * @author zero
     */
    protected function fetch(string $template = '', array $vars = []): string
    {
        return $this->view->fetch($template, $vars);
    }

    /**
     * 渲染内容输出
     *
     * @param string $content 模板内容
     * @param array $vars    模板输出变量
     * @return string
     * @author zero
     */
    protected function display(string $content = '', array $vars = []): string
    {
        return $this->view->display($content, $vars);
    }

    /**
     * 模板变量赋值
     *
     * @param string $name  要显示的模板变量
     * @param string $value 变量的值
     * @return $this
     * @author zero
     */
    protected function assign(string $name, string $value = ''): Addons
    {
        $this->view->assign([$name => $value]);
        return $this;
    }

    /**
     * 初始化模板引擎
     *
     * @access protected
     * @param array|string $engine 引擎参数
     * @return $this
     * @author zero
     */
    protected function engine(array|string $engine): Addons
    {
        $this->view->engine($engine);
        return $this;
    }

    /**
     * 插件标识信息
     *
     * @return string
     * @author zero
     */
    final protected function getName(): string
    {
        $class = get_class($this);
        list(, $name, ) = explode('\\', $class);
        $this->request->addon = $name;
        return $name;
    }

    /**
     * 插件基础信息
     *
     * @return array
     * @author zero
     */
    final public function getInfo(): array
    {
        $info = Config::get($this->addonInfo, []);
        if ($info) {
            return $info;
        }

        $info = $this->info ?? [];
        $infoFile = $this->addonPath . 'service.ini';

        if (is_file($infoFile)) {
            $fIni = parse_ini_file($infoFile, true, INI_SCANNER_TYPED) ?: [];
            $info = array_merge($info, $fIni);
        }
        Config::set($info, $this->addonInfo);
        return $info ?? [];
    }

    /**
     * 获取配置信息
     *
     * @param bool $type 是否获取完整配置
     * @return array
     * @author zero
     */
    final public function getConfig(bool $type): array
    {
        $config = Config::get($this->addonConfig, []);
        if ($config) {
            return $config;
        }

        $configFile = $this->addonPath . 'config.php';
        if (is_file($configFile)) {
            $tempArr = (array) include $configFile;
            if ($type) {
                return $tempArr;
            }
            foreach ($tempArr as $key => $value) {
                $config[$key] = $value['value'];
            }
            unset($tempArr);
        }

        Config::set($config, $this->addonConfig);
        return $config;
    }

    /**
     * 设置插件信息数据
     *
     * @param string $name (键名)
     * @param array $value (值)
     * @return array
     * @author zero
     */
    final public function setInfo(string $name = '', array $value = []): array
    {
        if (empty($name)) {
            $name = $this->getName();
        }

        $info = $this->getInfo($name);
        $info = array_merge($info, $value);
        Config::set($info,$name);
        return $info;
    }

    /**
     * 插件安装方法
     */
    abstract public function install();

    /**
     * 插件卸载方法
     */
    abstract public function uninstall();

    /**
     * 插件启用方法
     */
    abstract public function enabled();

    /**
     * 插件禁用方法
     */
    abstract public function disabled();
}
