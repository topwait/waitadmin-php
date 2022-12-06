<?php
declare(strict_types=1);

namespace think;

use think\facade\Config;
use think\facade\View;

abstract class Addons
{
    // APP容器
    protected $app;
    // 请求对象
    protected $request;
    // 插件路径
    protected $addonPath;
    // 插件标识
    protected $name;
    // 视图模型
    protected $view;
    // 插件配置
    protected $addonConfig;
    // 插件信息
    protected $addonInfo;

    /**
     * 构造函数
     *
     * Addons constructor.
     * @param \think\App $app
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
     * @author wait
     * @param string $template     模板文件名
     * @param array $vars          模板输出变量
     * @return false|mixed|string  响应模板内容
     * @throws \think\Exception
     */
    protected function fetch($template = '', $vars = [])
    {
        return $this->view->fetch($template, $vars);
    }

    /**
     * 渲染内容输出
     *
     * @author wait
     * @param  string $content 模板内容
     * @param  array  $vars    模板输出变量
     * @return mixed
     */
    protected function display($content = '', $vars = [])
    {
        return $this->view->display($content, $vars);
    }

    /**
     * 模板变量赋值
     *
     * @author wait
     * @param  mixed $name  要显示的模板变量
     * @param  mixed $value 变量的值
     * @return $this
     */
    protected function assign($name, $value = '')
    {
        $this->view->assign([$name => $value]);
        return $this;
    }

    /**
     * 初始化模板引擎
     * @access protected
     * @param  array|string $engine 引擎参数
     * @return $this
     */
    protected function engine($engine)
    {
        $this->view->engine($engine);

        return $this;
    }

    /**
     * 插件标识信息
     *
     * @author wait
     * @return string
     */
    final protected function getName()
    {
        $class = get_class($this);
        list(, $name, ) = explode('\\', $class);
        $this->request->addon = $name;
        return $name;
    }

    /**
     * 插件基础信息
     *
     * @author wait
     * @return array
     */
    final public function getInfo()
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
        return isset($info) ? $info : [];
    }

    /**
     * 获取配置信息
     *
     * @author wait
     * @param bool $type 是否获取完整配置
     * @return array
     */
    final public function getConfig($type = false)
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
            foreach ($temp_arr as $key => $value) {
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
     * @author wait
     * @param $name (键名)
     * @param array $value (值)
     * @return array
     */
    final public function setInfo($name = '', $value = [])
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
