<?php


namespace addons\curd;


use Exception;
use think\Addons;

class Plugin extends Addons
{
    /**
     * 插件安装方法
     *
     * @throws Exception
     * @return bool
     * @author windy
     */
    public function install(): bool
    {
        return true;
    }

    /**
     * 插件卸载方法
     *
     * @throws Exception
     * @return bool
     * @author windy
     */
    public function uninstall(): bool
    {
        return true;
    }

    /**
     * 插件启用方法
     *
     * @return bool
     * @author windy
     */
    public function enabled(): bool
    {
        return true;
    }

    /**
     * 插件禁用方法
     *
     * @return bool
     * @author windy
     */
    public function disabled(): bool
    {
        return true;
    }
}