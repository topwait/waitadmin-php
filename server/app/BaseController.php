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
declare (strict_types = 1);

namespace app;

use app\common\enums\ErrorEnum;
use app\common\exception\RequestException;
use app\common\exception\SystemException;
use Exception;
use ReflectionMethod;
use think\App;
use think\exception\HttpResponseException;
use think\facade\Lang;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected \think\Request $request;

    /**
     * 应用实例
     * @var App
     */
    protected App $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected bool $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected array $middleware = [];

    /**
     * 构造方法
     *
     * @param App $app 应用对象
     * @author windy
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;
        $this->intercept($this->request->controller());
        $this->loadLang($this->request->controller());
    }

    /**
     * 调用方法
     *
     * @param $name
     * @param $arguments
     * @return void
     * @throws SystemException
     * @author windy
     */
    public function __call($name, $arguments): void
    {
        $errCode = ErrorEnum::METHOD_ERROR;
        $message = ErrorEnum::getMsgByCode($errCode);
        throw new SystemException($message, $errCode);
    }

    /**
     * 验证数据
     *
     * @param array $data 数据
     * @param array|string $validate 验证器名或者验证规则数组
     * @param array $message 提示信息
     * @param bool $batch 是否批量验证
     * @return bool
     * @author windy
     */
    protected function validate(array $data, array|string $validate, array $message = [], bool $batch = false): bool
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                [$validate, $scene] = explode('.', $validate);
            }
            $class = str_contains($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

    /**
     * GET异步请求
     *
     * @return bool
     * @author windy
     */
    protected function isAjaxGet(): bool
    {
        return $this->request->isAjax() && $this->request->isGet();
    }

    /**
     * POST异步请求
     *
     * @return bool
     * @author windy
     */
    protected function isAjaxPost(): bool
    {
        return $this->request->isAjax() && $this->request->isPost();
    }

    /**
     * 重写跳转方法
     *
     * @param mixed ...$args
     * @author windy
     */
    protected function redirect(...$args): void
    {
        throw new HttpResponseException(redirect(...$args));
    }

    /**
     * 加载语言
     *
     * @param string $name (功能模块)
     * @author windy
     */
    protected function loadLang(string $name): void
    {
        $lang = $this->request->cookie(config('lang.cookie_var'));
        $lang = $lang ? preg_match("/^([a-zA-Z\-_]{2,10})\$/i", $lang) : 'zh-cn';
        $root = str_replace('\\', '/', root_path());
        $apps = str_replace('\\', '/', app_path());
        $name = strtolower(str_replace('.', '/', $name));

        if (is_file($root . '/app/common/lang/' . $lang . '.php')) {
            Lang::load($root . '/app/common/lang/' . $lang . '.php');
        }

        if (is_file($apps . 'lang/' . $lang . '.php')) {
            Lang::load($apps . 'lang/' . $lang . '.php');
        }

        if (is_file($apps . 'lang/' . $lang . '/'. $name . '.php')) {
            Lang::load($apps . 'lang/' . $lang . '/'. $name . '.php');
        }
    }

    /**
     * 请求方式拦截器
     *
     * @param $controller
     * @author windy
     */
    private function intercept($controller): void
    {
        try {
            $controller = str_replace('.', '\\', $controller) . 'Controller';
            $namespaces = '\\' . $this->app->getNamespace() . '\\controller\\' . $controller;
            $reflection = new ReflectionMethod($namespaces, $this->request->action());

            if (!$reflection->getDocComment()) {
                return;
            }

            $annotate = [];
            $patterns = '/@\w+\s+(.*)/u';
            preg_match_all($patterns, $reflection->getDocComment(), $matches);
            foreach ($matches[0] as $item) {
                $arr = explode(' ', $item);
                $key = trim($arr[0]);
                $val = trim($arr[1]);
                $annotate[$key] = $val;
            }

            if (isset($annotate['@method']) && !trim($annotate['@method'])) {
                throw new RequestException("Method annotation configuration error");
            }

            if (!empty($annotate['@method'])) {
                $methods = str_replace('[', '', $annotate['@method']);
                $methods = str_replace(']', '', $methods);
                $methods = explode('|', trim($methods));

                $supported = [];
                foreach ($methods as $item) {
                    $supported[] = trim($item);
                    $strMethod = trim($item);
                    if (!ctype_upper($strMethod)) {
                        throw new RequestException("Request method '$strMethod' must be uppercase");
                    }
                }

                $reqMethod = $this->request->method();
                if (!in_array($reqMethod, $supported)) {
                    throw new RequestException("Request method '$reqMethod' not supported");
                }
            }

            return;
        } catch (Exception) {}
    }
}
