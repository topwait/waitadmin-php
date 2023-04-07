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

/**
 * 安装配置验证类
 *
 * Class proof
 */
class Proof
{
    /**
     * 是否允许下一步
     *
     * @var bool
     * @author zero
     */
    private bool $allowNext = true;

    /**
     * 是否允许下一步
     * @return bool
     */
    public function checkAllowNext(): bool
    {
        return $this->allowNext;
    }

    /**
     * 当前PHP版本是否合格
     *
     * @return string
     * @author zero
     */
    public function checkPHP(): string
    {
        return version_compare(PHP_VERSION, '8.0.0') >= 0 ? 'ok' : 'fail';
    }

    /**
     * 是否安装PDO
     *
     * @return string
     * @author zero
     */
    #[Pure]
    public function checkPDO(): string
    {
        return extension_loaded('pdo') ? 'ok' : 'fail';
    }

    /**
     * 是否安装PdoMsql
     *
     * @return string
     * @author zero
     */
    #[Pure]
    public function checkPDOMySQL(): string
    {
        return extension_loaded('pdo_mysql') ? 'ok' : 'fail';
    }

    /**
     * 是否支持JSON
     *
     * @return string
     * @author zero
     */
    #[Pure]
    public function checkJSON(): string
    {
        return extension_loaded('json') ? 'ok' : 'fail';
    }

    /**
     * 是否支持
     *
     * @return string
     * @author zero
     */
    #[Pure]
    public function checkOpenssl(): string
    {
        return extension_loaded('openssl') ? 'ok' : 'fail';
    }

    /**
     * 是否支持zlib
     *
     * @return string
     * @author zero
     */
    #[Pure]
    public function checkZlib(): string
    {
        return extension_loaded('zlib') ? 'ok' : 'fail';
    }

    /**
     * 是否支持curl
     *
     * @return string
     * @author zero
     */
    #[Pure]
    public function checkCurl(): string
    {
        return extension_loaded('curl') ? 'ok' : 'fail';
    }

    /**
     * 是否支持GD2
     *
     * @return string
     * @author zero
     */
    #[Pure]
    public function checkGd2(): string
    {
        return extension_loaded('gd') ? 'ok' : 'fail';
    }

    /**
     * 是否支持Dom
     *
     * @return string
     * @author zero
     */
    #[Pure]
    public function checkDom(): string
    {
        return extension_loaded('dom') ? 'ok' : 'fail';
    }

    /**
     * 是否支持FileInfo
     *
     * @return string
     * @author zero
     */
    #[Pure]
    public function checkFileInfo(): string
    {
        return extension_loaded('fileinfo') ? 'ok' : 'fail';
    }

    /**
     * 检查目录是否可写
     *
     * @param $dir
     * @return string
     * @author zero
     */
    #[Pure]
    public function checkDirWrite($dir=''): string
    {
        $route = APP_ROOT . '/' .$dir;
        return is_writable($route) ? 'ok' : 'fail';
    }

    /**
     * 检查目录是否可读
     *
     * @param $dir
     * @return string
     * @author zero
     */
    #[Pure]
    public function checkDirRead($dir=''): string
    {
        $route = APP_ROOT . '/' .$dir;
        return is_readable($route) ? 'ok' : 'fail';
    }

    /**
     * 检查参数情况
     *
     * @param $post
     * @return string
     * @author zero
     */
    #[Pure]
    public function checkParams($post): string
    {
        if (!$post['host'])     return '数据库主机不可为空';
        if (!$post['port'])     return '数据库端口不可为空';
        if (!$post['username']) return '数据库用户不可为空';
        if (!$post['password']) return '数据库密码不可为空';
        if (!$post['db'])       return '数据库名称不可为空';
        if (!$post['prefix'])   return '数据库前缀不可为空';
        if (!$post['admin_user']) return '管理员账号不可为空';
        if (!$post['admin_pwd'])  return '管理员密码不可为空';
        if (strlen($post['admin_pwd']) < 6)  return '管理员密码不能少于6位数';
        if (!$post['admin_pwd_confirm'])  return '确认密码不可为空';
        if ($post['admin_pwd'] !== $post['admin_pwd_confirm']) {
            return '两次密码不一致';
        }
        return '';
    }

    /**
     * 磁盘容量
     *
     * @return string
     * @author zero
     */
    #[Pure]
    public function freeDiskSpace(): string
    {
        $freeDiskSpace = disk_free_space(realpath(__DIR__)) / 1024 / 1024;
        if ($freeDiskSpace > 1024) {
            return number_format($freeDiskSpace / 1024, 2) . 'G';
        }

        return number_format($freeDiskSpace, 2) . 'M';
    }

    /**
     * 通过或失败图标
     * @param $status
     * @return string
     */
    public function successOrFail($status): string
    {
        if ($status == 'ok')
            return '<img src="./static/icon/ic_ok.png" class="icon" alt="icon">';

        $this->allowNext = false;
        return '<img src="./static/icon/ic_error.png" class="icon" alt="icon">';
    }
}