<?php


use JetBrains\PhpStorm\Pure;

class Util
{
    /**
     * 生成配置文件模板
     *
     * @param $post
     * @return bool|int
     * @author windy
     */
    public function makeEnv($post): bool|int
    {
        $environment = file_get_contents(INSTALL_ROOT . '/template/env.php');
        $environment = str_replace('#DB_HOST#', $post['host'], $environment);
        $environment = str_replace('#DB_NAME#', $post['db'], $environment);
        $environment = str_replace('#DB_USER#', $post['username'], $environment);
        $environment = str_replace('#DB_PWD#', $post['password'], $environment);
        $environment = str_replace('#DB_PORT#', $post['port'], $environment);
        $environment = str_replace('#DB_PREFIX#', $post['prefix'], $environment);

        return file_put_contents (APP_ROOT.'/.env' , $environment);
    }

    /**
     * 生成菜单模板
     *
     * @author windy
     */
    public function makeTpl(): void
    {
        $indexTreeRc = APP_ROOT . '/app/backend/view/index/uu.html';
        $indexTreeTo = file_get_contents(INSTALL_ROOT . '/pages/index_tree.html');
        file_put_contents($indexTreeRc, $indexTreeTo);

        $kernelTreeJsRc = APP_ROOT . '/public/static/backend/js/kk.js';
        $kernelTreeJsTo = file_get_contents(INSTALL_ROOT . '/pages/kernel_tree.js');
        file_put_contents($kernelTreeJsRc, $kernelTreeJsTo);
    }

    /**
     * 生成锁定文件
     *
     * @return bool|int
     * @author windy
     */
    public function makeLock(): bool|int
    {
        return file_put_contents(APP_ROOT . '/install.lock', '');
    }

    /**
     * 加载锁文件
     *
     * @return bool
     * @author windy
     */
    #[Pure]
    public function loadLock(): bool
    {
        return file_exists(APP_ROOT . '/install.lock');
    }

    /**
     * 替换访问入口
     *
     * @param $name (入口名称)
     * @author windy
     */
    public function replaceEntrance($name)
    {
        $key = 'backend_entrance';
        $appConfig = APP_ROOT . '/config/app.php';
        $config = file_get_contents($appConfig);

        // 原始入口
        $re = [];
        preg_match_all("/'{$key}'.*?=>.*?'(.*?)'/", $config, $re);
        $costEnter = trim($re[1][0], '/');

        // 替换配置
        $config = preg_replace("/'{$key}'.*?=>.*?'.*?'/", "'{$key}' => '/{$name}'", $config);
        file_put_contents($appConfig, $config);

        // 替换入口
        $enterFile = PUBLIC_ROOT . '/'. trim($costEnter);
        if (file_exists($enterFile)) {
            rename($enterFile, PUBLIC_ROOT . '/'. $name);
        }
    }
}