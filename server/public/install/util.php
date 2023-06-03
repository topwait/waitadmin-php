<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统 (安装界面不允许迁移到别的程序使用)
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

use JetBrains\PhpStorm\Pure;

class Util
{
    /**
     * 生成配置文件模板
     *
     * @param $post
     * @return bool|int
     * @author zero
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
        $environment = str_replace('#PROJECT_BACKEND#', $post['backend_entrance'], $environment);

        return file_put_contents (APP_ROOT.'/.env' , $environment);
    }

    /**
     * 生成菜单模板 (树形)
     *
     * @author zero
     */
    public function makeTreeTpl(): void
    {
        $cssTreeJsRc = APP_ROOT . '/public/static/backend/css/kernel.css';
        $cssTreeJsTo = file_get_contents(INSTALL_ROOT . '/pages/css_tree.tp');
        file_put_contents($cssTreeJsRc, $cssTreeJsTo);

        $indexTreeRc = APP_ROOT . '/app/backend/view/index/index.html';
        $indexTreeTo = file_get_contents(INSTALL_ROOT . '/pages/index_tree.tp');
        file_put_contents($indexTreeRc, $indexTreeTo);

        $kernelTreeJsRc = APP_ROOT . '/public/static/backend/js/kernel.min.js';
        $kernelTreeJsTo = file_get_contents(INSTALL_ROOT . '/pages/kernel_tree.tp');
        file_put_contents($kernelTreeJsRc, $kernelTreeJsTo);
    }

    /**
     * 生成菜单模板 (呼出)
     *
     * @author zero
     */
    public function makeCallTpl(): void
    {
        $cssTreeJsRc = APP_ROOT . '/public/static/backend/css/kernel.css';
        $cssTreeJsTo = file_get_contents(INSTALL_ROOT . '/pages/css_call.tp');
        file_put_contents($cssTreeJsRc, $cssTreeJsTo);

        $indexTreeRc = APP_ROOT . '/app/backend/view/index/index.html';
        $indexTreeTo = file_get_contents(INSTALL_ROOT . '/pages/index_call.tp');
        file_put_contents($indexTreeRc, $indexTreeTo);

        $kernelTreeJsRc = APP_ROOT . '/public/static/backend/js/kernel.min.js';
        $kernelTreeJsTo = file_get_contents(INSTALL_ROOT . '/pages/kernel_call.tp');
        file_put_contents($kernelTreeJsRc, $kernelTreeJsTo);
    }

    /**
     * 生成锁定文件
     *
     * @return bool|int
     * @author zero
     */
    public function makeLock(): bool|int
    {
        return file_put_contents(APP_ROOT . '/install.lock', '');
    }

    /**
     * 加载锁文件
     *
     * @return bool
     * @author zero
     */
    #[Pure]
    public function loadLock(): bool
    {
        return file_exists(APP_ROOT . '/install.lock');
    }

    /**
     * 替换访问入口
     *
     * @param $name (新入口名称)
     * @param $oldName (旧入口名称)
     * @author zero
     */
    public function replaceEntrance(string $name, string $oldName)
    {
        // 替换配置
        $environment = file_get_contents(APP_ROOT . '/.env');
        $environment = str_replace('/admin.php', '/' . $name, $environment);
        file_put_contents (APP_ROOT.'/.env' , $environment);

        // 替换入口
        $enterFile = PUBLIC_ROOT . '/'. trim($oldName);
        if (file_exists($enterFile)) {
            rename($enterFile, PUBLIC_ROOT . '/'. $name);
        }
    }

    /**
     * 查询入口文件
     *
     * @return string
     * @author zero
     */
    public function queryEntranceFile(): string
    {
        $backendEntrance = 'admin.php';
        if ($handle = opendir(PUBLIC_ROOT)) {
            while (false !== ($file = readdir($handle))) {
                if (str_ends_with($file, '.php')) {
                    $filePath = str_replace('\\', '/', PUBLIC_ROOT . '/' . $file);
                    $fileContents = file_get_contents($filePath);
                    $checkStr01 = 'namespace think;';
                    $checkStr02 = '$http = (new App())->http;';
                    $checkStr03 = '$response->send();';
                    $checkStr04 = '$http->end($response);';
                    $checkStr05 = '$response = $http->name(\'backend\')->run();';
                    if (str_contains($fileContents, $checkStr01) &&
                        str_contains($fileContents, $checkStr02) &&
                        str_contains($fileContents, $checkStr03) &&
                        str_contains($fileContents, $checkStr04) &&
                        str_contains($fileContents, $checkStr05)
                    ) {
                        $backendEntrance = strval($file);
                    }
                    break;
                }
            }
            closedir($handle);
        }

        return $backendEntrance;
    }
}