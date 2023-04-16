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

namespace app\common\command;

use app\common\model\auth\AuthAdmin;
use app\common\utils\FileUtils;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Cache;

/**
 * 系统工具指令
 */
class Wa extends Command
{
    /**
     * 指令配置
     */
    protected function configure()
    {
        $this->setName('wa')
            ->setDescription('工具指令')
            ->addArgument('op', Argument::OPTIONAL, "操作类型")
            ->addOption('name', null, Option::VALUE_REQUIRED, '名称')
            ->addOption('account', null, Option::VALUE_REQUIRED, '账号')
            ->addOption('password', null, Option::VALUE_REQUIRED, '密码');
    }

    /**
     * 执行指令
     *
     * @param Input $input
     * @param Output $output
     * @return bool|int|null
     */
    protected function execute(Input $input, Output $output): bool|int|null
    {
        if (!$input->getArgument('op')) {
            echo "\n";
            echo "===============指令工具箱==============\n";
            echo "(1) 修改后台密码   (2) 清除登录限制\n";
            echo "(3) 修改入口路径   (4) 清除系统缓存\n";
            echo "=======================================\n";
            echo "请携带命令编号如: php think wa 1\n";
            echo "\n";
        }

        switch (intval($input->getArgument('op'))) {
            case 1: // 修改后台密码
                $this->changePassword($input);
                break;
            case 2: // 清除登录限制
                $this->cleanLoginLimit($input);
                break;
            case 3: // 修改入口路径
                $this->changeEntrance($input);
                break;
            case 4: // 清除系统缓存
                $this->cleanSysCache();
                break;
            default:
                echo '没找到“' . $input->getArgument('op') . '”相关指令';
                return false;
        }

        return true;
    }

    /**
     * 修改后台密码
     *
     * @param Input $input
     * @return void
     */
    private function changePassword(Input $input): void
    {
        $account  = $input->getOption('account');
        $password = $input->getOption('password');
        if (!$account)  {
            echo 'Error: --account参数缺失!';
            return;
        }

        if (!$password) {
            echo 'Error: --password参数缺失!';
            return;
        }

        if (strlen($password) < 6 || strlen($password) > 18) {
            echo 'Error: 密码必须在6~18个字符内!';
            return;
        }

        $model = new AuthAdmin();
        $admin = $model
            ->field('id,username,salt')
            ->where(['username'=>$account])
            ->where(['is_delete'=>0])
            ->findOrEmpty()
            ->toArray();

        if (!$admin) {
            echo 'Error: 很抱歉,没有查找该账号信息!';
            return;
        }

        $newPassword = make_md5_str(trim($password), $admin['salt']);
        AuthAdmin::update([
            'password'    => $newPassword,
            'update_time' => time()
        ], ['id'=>$admin['id']]);

        echo 'Successful~~~';
    }

    /**
     * 清除登录限制
     *
     * @param Input $input
     */
    private function cleanLoginLimit(Input $input): void
    {
        $account  = $input->getOption('account');
        if (!$account)  {
            echo 'Error: --account参数缺失!';
            return;
        }

        Cache::delete('login:fail:'.$account);
        echo 'successful~~~';
    }

    /**
     * 修改后台入口
     *
     * @param Input $input
     */
    private function changeEntrance(Input $input): void
    {
        $name = $input->getOption('name');
        if (!$name)  {
            echo 'Error: --name参数缺失!';
            return;
        }

        if (count(explode('.', $name)) != 2) {
            echo 'Error: 参数值格式必须是: xxx.php';
            return;
        }

        $key = 'backend_entrance';
        $appConfig = root_path() . '/config/app.php';
        $config = file_get_contents($appConfig);

        // 原始入口
        $re = [];
        preg_match_all("/'$key'.*?=>.*?'(.*?)'/", $config, $re);
        $costEnter = trim(trim($re[1][0]), '/');

        // 替换配置
        $config = preg_replace("/'$key'.*?=>.*?'.*?'/", "'$key' => '/$name'", $config);
        file_put_contents($appConfig, $config);

        // 替换入口
        $enterFile = PUBLIC_ROOT . '/'. trim($costEnter);
        if (file_exists($enterFile)) {
            rename($enterFile, PUBLIC_ROOT . '/'. $name);
        }
        echo 'successful~~~';
    }

    /**
     * 清除系统缓存
     */
    public function cleanSysCache(): void
    {
        FileUtils::rmdir(root_path().'runtime/cache/');
        FileUtils::rmdir(root_path().'runtime/api/');
        FileUtils::rmdir(root_path().'runtime/backend/');
        FileUtils::rmdir(root_path().'runtime/frontend/');
        FileUtils::rmdir(root_path().'runtime/generate/');
    }
}