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

include_once 'proof.php';
include_once 'mysql.php';
include_once 'util.php';

// 常量定义
const INSTALL_ROOT = __DIR__;
define('PUBLIC_ROOT', dirname(__DIR__));
define('APP_ROOT', dirname(__DIR__, 2));

// 参数实例化
$successTables = [];
$errMsg = null;
$step   = $_GET['step'] ?? 1;
$ajax   = $_POST['ajax'] ?? 0;
$proof  = new Proof();
$util   = new Util();

// 安装验证
if ($util->loadLock() && in_array($step, [1, 2, 3, 4])) {
    die('可能已经安装过本系统了，请删除根目录下面的install.lock文件再尝试！');
}

// 流程校验
if (!in_array($step, [1, 2, 3, 4, 5]))  {
    die('你想干嘛？能不能好按流程操作？');
}

// 后台访问名
$number = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$adminName = substr(str_shuffle($number), 0, 10) . '.php';

// 数据库校验
if ($step == 4) {
    $post = [
        'host'       => $_POST['host'] ?? '',
        'port'       => $_POST['port'] ?? '',
        'db'         => $_POST['db'] ?? '',
        'username'   => $_POST['username'] ?? '',
        'password'   => $_POST['password'] ?? '',
        'prefix'     => $_POST['prefix'] ?? '',
        'clear'      => !empty($_POST['clear']),
        'layout'     => $_POST['layout'] ?? 'exhale',
        'admin_user' => $_POST['admin_user'] ?? '',
        'admin_pwd'  => $_POST['admin_pwd'] ?? '',
        'admin_pwd_confirm'  => $_POST['admin_pwd_confirm'] ?? ''
    ];

    // 连接数据库
    $mysql = new Mysql($post);

    // 异步验证
    $errMsg = $proof->checkParams($post);
    if ($ajax) {
        // 参数验证
        if ($errMsg) {
            exit(json_encode(['code'=>1, 'msg'=>$errMsg]));
        }
        // 数据库验证
        $mysqlErr = $mysql->checkDB();
        if ($mysqlErr !== true) {
            exit(json_encode(['code'=>1, 'msg'=>$mysqlErr]));
        }
        // 验证通过返回
        exit(json_encode(['code'=>0, 'msg'=>'success']));
    } else {
        // 同步验证
        if ($errMsg) {
            $step = 3;
        } else {
            $indexTreeRc = APP_ROOT . '/app/backend/view/index/';
            $jsTreeRc = APP_ROOT . '/public/static/backend/js';
            $mysqlErr = $mysql->checkDB();
            if ($mysqlErr !== true) {
                $errMsg = $mysqlErr;
                $step = 3;
            } else {
                // 写入到数据表
                $successTables = $mysql->install();
                if (is_string($successTables)) {
                    $errMsg = $successTables;
                    $step = 3;
                }

                // 生成配置文件
                $util->makeEnv($post);

                // 生成锁定文件
                $util->makeLock();

                // 左侧菜单生成
                if ($post['layout'] == 'tree') {
                    $util->makeTreeTpl();
                } else {
                    $util->makeCallTpl();
                }
            }
        }
    }
}

// 生成入口文件
if ($step == 5) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $util->replaceEntrance($adminName);
    } else {
        $key = 'backend_entrance';
        $appConfig = APP_ROOT . '/config/app.php';
        $config = file_get_contents($appConfig);

        $re = [];
        preg_match_all("/'{$key}'.*?=>.*?'(.*?)'/", $config, $re);
        $adminName = trim($re[1][0], '/');
    }
}

// 输出模板
include_once __DIR__ . '/template/main.php';