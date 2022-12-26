<?php

namespace app\common\command;

use app\common\exception\OperateException;
use app\common\utils\MenuUtils;
use app\common\utils\ZipUtils;
use Exception;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\addons\Service as AddonService;
use ZipArchive;

/**
 * 插件管理指令
 */
class Addon extends Command
{
    /**
     * 指令配置
     */
    protected function configure()
    {
        $this->setName('addon')
            ->setDescription('插件管理指令')
            ->addArgument('op', Argument::OPTIONAL, "操作类型")
            ->addArgument('name', Argument::OPTIONAL, "插件名称");
    }

    /**
     * 执行指令
     *
     * @param Input $input
     * @param Output $output
     * @return bool|int|null
     * @throws OperateException
     */
    protected function execute(Input $input, Output $output): bool|int|null
    {
        if (!$input->getArgument('op')) {
            echo "\n";
            echo "install --name [name]   : 安装插件\n";
            echo "uninstall --name [name] : 卸载插件\n";
            echo "enabled --name [name]   : 启用插件\n";
            echo "disabled --name [name]  : 禁用插件\n";
            echo "\n";
        }

        if (!$input->hasArgument('name')) {
            echo 'name参数缺失';
            return false;
        }

        $name = $input->getArgument('name');
        switch ($input->getArgument('op')) {
            case 'install':
                $this->install($name);
                break;
            case 'uninstall';
                $this->uninstall($name);
                break;
            case 'enabled':
                $this->enabled($name);
                break;
            case 'disabled':
                $this->disabled($name);
                break;
            default:
                echo '没找到“' . $input->getArgument('op') . '”相关指令';
                return false;
        }

        return true;
    }

    /**
     * 插件安装
     *
     * @param string $name
     * @throws OperateException
     * @author windy
     */
    private function install(string $name)
    {
        // 获取本地的插件库
        $localAddons = get_addons_list()??[];
        list($addons, $addonNames) = [$localAddons, array_keys($localAddons)];

        // 检查插件是否安装
        if (in_array($name, $addonNames) && ($addons[$name]['install']??0)) {
            throw new OperateException('插件'.$name.'已安装');
        }

        // 检查插件是否就绪
        $class = get_addons_instance($name);
        if (empty($class)) {
            throw new OperateException('插件'.$name.'未就绪');
        }

        // 安装插件应用
        try {
            // 安装插件方法
            $class->install();

            // 安装插件菜单
            if (!empty($class->menus)) {
                MenuUtils::create($class->menus);
            }

            // 安装插件SQL
            install_addons_sql($name);

            // 更新插件状态
            $addons[$name]['status']  = 1;
            $addons[$name]['install'] = 1;
            set_addons_info($name, $addons[$name]);

            // 拷贝插件应用
            AddonService::installAddonsApp($name, true);
        } catch (Exception $e) {
            throw new OperateException($e->getMessage());
        }
    }

    /**
     * 插件卸载
     *
     * @param string $name
     * @throws OperateException
     * @author windy
     */
    private function uninstall(string $name)
    {
        // 验证插件名称
        if (!preg_match("/^[a-zA-Z0-9]+$/", $name)) {
            throw new OperateException('插件名称不正确');
        }

        // 验证插件存在
        $addonPath = app()->getRootPath() . 'addons' . DS . $name . DS;
        if (!is_dir($addonPath)) {
            throw new OperateException('卸载插件不存在');
        }

        // 验证插件状态
        $ini = get_addons_info($name);
        if (!$ini || !$ini['install']) {
            throw new OperateException('插件不存在或已卸载');
        }

        if ($ini['status']) {
            throw new OperateException('请先禁用插件');
        }

        // 移除插件应用
        AddonService::uninstallAddonsApp($name);

        // 备份插件应用
        try {
            $source = root_path() . 'addons' . DS . $name . DS;
            $target = root_path() . 'runtime' . DS . 'addons' . DS . $name . '-backup-' . date("YmdHis") . '.zip';
            ZipUtils::zip($source, $target);
        } catch (Exception) {}

        try {
            // 执行插件卸载
            $class = get_addons_instance($name);
            $class->uninstall();
            if (!empty($class->menus)) {
                MenuUtils::delete($name);
            }

            // 卸载插件SQL
            uninstall_addons_sql($name);

            $ini['status'] = 0;
            $ini['install'] = 0;
            set_addons_info($name, $ini);
        } catch (Exception $e) {
            throw new OperateException($e->getMessage());
        }
    }

    /**
     * 启用插件
     *
     * @param string $name
     * @throws OperateException
     * @author windy
     */
    private function enabled(string $name)
    {
        // 校验插件配置
        $ini = get_addons_info($name);
        if (!$ini) {
            throw new OperateException('插件配置文件异常');
        }

        // 校验插件状态
        if ($ini['status']) {
            throw new OperateException('插件已是启用状态');
        }

        // 校验插件对象
        $class = get_addons_instance($name);
        if (empty($class)) {
            throw new OperateException('插件'.$name.'未就绪');
        }

        // 验证冲突文件
        $this->isConflict($name);

        // 备份冲突文件
        $files = AddonService::getGlobalAddonsFiles($name, true);
        if ($files) {
            $zip = new ZipArchive();
            try {
                foreach ($files as $file) {
                    $zip->addFile(root_path() . $file, $file);
                }
                $basesPath = root_path() . 'runtime' . DS . 'addons'. DS . $name;
                $backupDir = $basesPath . '-conflict-enable-' . date('YmdHis') . 'zip';
                $zip->extractTo($backupDir);
            } catch (Exception) { } finally {
                $zip->close();
            }
        }

        try {
            // 执行启用插件
            AddonService::installAddonsApp($name);
            $class->enabled();
            if (!empty($class->menus)) {
                MenuUtils::enable($name);
            }

            // 更新插件配置
            $ini['status'] = 1;
            set_addons_info($name, $ini);
        } catch (Exception $e) {
            throw new OperateException($e->getMessage());
        }
    }

    /**
     * 插件禁用
     *
     * @param string $name
     * @throws OperateException
     * @author windy
     */
    private function disabled(string $name)
    {
        // 校验插件配置
        $ini = get_addons_info($name);
        if (!$ini) {
            throw new OperateException('插件配置文件异常');
        }

        // 校验插件状态
        if (!$ini['status']) {
            throw new OperateException('插件已是禁用状态');
        }

        // 校验插件对象
        $class = get_addons_instance($name);
        if (empty($class)) {
            throw new OperateException('插件'.$name.'未就绪');
        }

        try {
            // 禁用插件应用
            AddonService::uninstallAddonsApp($name);
            $class->disabled();
            if (!empty($class->menus)) {
                MenuUtils::disable($name);
            }

            // 更新插件配置
            $ini['status'] = 0;
            set_addons_info($name, $ini);
        } catch (Exception $e) {
            throw new OperateException($e->getMessage());
        }
    }

    /**
     * 冲突检测
     *
     * @param string $name (插件名称)
     * @throws OperateException
     * @author windy
     */
    private function isConflict(string $name): void
    {
        $list = AddonService::getGlobalAddonsFiles($name, true);
        if ($list) {
            $errCode = 5910;
            $message = '找到冲突的文件';
            throw new OperateException($message, $errCode, $list);
        }
    }
}