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

class Mysql
{
    private PDO|string $pdo;
    private $db;
    private $host;
    private $port;
    private $username;
    private $password;
    private $prefix;
    private string $encoding;
    private $clear;
    private array $successTable;
    private array $post;

    /**
     * 构造方法
     *
     * Mysql constructor.
     * @param array $post
     */
    public function __construct(array $post)
    {
        $this->post     = $post;
        $this->db       = $post['db'];
        $this->port     = $post['port'];
        $this->host     = $post['host'];
        $this->username = $post['username'];
        $this->password = $post['password'];
        $this->prefix   = $post['prefix'];
        $this->clear    = $post['clear'];
        $this->encoding = 'utf8mb4';
        $this->pdo = $this->connect();
    }

    /**
     * 连接数据库
     *
     * @return PDO|string
     * @author zero
     */
    public function connect(): PDO|string
    {
        try {
            $dsn = "mysql:host={$this->host}; port={$this->port}";
            $db = new PDO($dsn, $this->username, $this->password);
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->exec("SET NAMES {$this->encoding}");
            try{
                $db->exec("SET GLOBAL sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';");
            }catch (Exception){}
            return $db;
        } catch (PDOException $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * 数据库版本号
     *
     * @return bool|string
     * @author zero
     */
    public function mysqlVersion(): bool|string
    {
        $sql = "SELECT VERSION() AS version";
        $result = $this->pdo->query($sql)->fetch();
        return substr($result->version, 0, 3);
    }

    /**
     * 检查数据库是否存在
     *
     * @return mixed
     * @author zero
     */
    public function dbExists(): mixed
    {
        $sql = "SHOW DATABASES like '{$this->db}'";
        return $this->pdo->query($sql)->fetch();
    }

    /**
     * 检查是否有表存在
     *
     * @return mixed
     * @author zero
     */
    public function tableExits(): mixed
    {
        $sql = "SHOW TABLES FROM {$this->db}";
        return $this->pdo->query($sql)->fetch();
    }

    /**
     * 删除数据表
     *
     * @return false|PDOStatement
     * @author zero
     */
    public function dropTable(): bool|PDOStatement
    {
        $sql = "drop database {$this->db};";
        return $this->pdo->query($sql);
    }

    /**
     * 创建数据库
     *
     * @param $version
     * @return bool|PDOStatement
     * @author zero
     */
    public function createDB($version): bool|PDOStatement
    {
        $sql = "CREATE DATABASE `{$this->db}`";
        if ($version > 4.1) $sql .= " DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
        return $this->pdo->query($sql);
    }

    /**
     * 创建数据表
     *
     * @param $version
     * @return bool
     * @throws Exception
     * @author zero
     */
    public function createTable($version): bool
    {
        // 加载数据
        $dbFile = INSTALL_ROOT . '/db/wait.sql';
        $tables = str_replace(";\r\n", ";\n", file_get_contents($dbFile));
        $tables = explode(";\n", $tables);
        array_push($tables, $this->initAccount());

        // 插入数据
        $millisecond = microtime(true) * 10000;
        foreach ($tables as $table) {
            // 空行处理
            $table = trim($table);
            if (empty($table)) continue;

            // 版本处理
            if (str_contains($table, 'CREATE') and $version <= 4.1) {
                $table = str_replace('DEFAULT CHARSET=utf8mb4', '', $table);
            }

            // 注解处理
            if (str_starts_with($table, '--')) continue;

            // 替换前缀
            $table = str_replace('`wait_', $this->db . '.`wait_', $table);
            $table = str_replace('`wait_', '`' . $this->prefix, $table);

            // 创建表格
            if (str_contains($table, 'CREATE')) {
                $tableName = explode('`', $table)[1];
                $millisecond += random_int(3000, 7000);
                $this->successTable[] = [$tableName, date('Y-m-d H:i:s', $millisecond / 10000)];
            }

            // 执行命令
            if (!$this->pdo->query($table)) return false;
        }

        return true;
    }

    /**
     * 初始化账号
     *
     * @return string
     * @author zero
     */
    public function initAccount(): string
    {
        $time = time();
        $username = $this->post['admin_user'];
        $password = $this->post['admin_pwd'];
        $avatar = '/static/backend/images/default/avatar.png';

        $salt = substr(md5($time . $username), 0, 6);
        $baseStr = md5('CORRECT');
        $password = md5($baseStr . $password.$salt);

        $sql  = "INSERT INTO"." `{$this->prefix}auth_admin` ";
        $sql .= "(`id`,`username`,`nickname`,`password`,`salt`,`avatar`,`create_time`,`update_time`) VALUES ";
        $sql .= "(1, '{$username}', '{$username}', '{$password}', '{$salt}', '{$avatar}', '{$time}', '{$time}');";
        return $sql;
    }

    /**
     * 验证数据库
     *
     * @return bool|string
     * @author zero
     */
    public function checkDB(): bool|string
    {
        if (!is_object($this->pdo)) {
            return '安装错误，请检查连接信息:'.mb_strcut($this->pdo,0,30).'...';
        }

        $version = $this->mysqlVersion();
        if (!$this->dbExists()) {
            if (!$this->createDB($version)) {
                return '创建数据库错误';
            }
        } elseif ($this->tableExits() and $this->clear == false) {
            return '数据表已经存在，您可以清空现有数据库继续安装';
        } elseif ($this->dbExists() and $this->clear == true) {
            if (!$this->dropTable()) {
                return '删除存在的数据库库出错了,请手动清除';
            } else {
                if (!$this->createDB($version)) {
                    return '创建数据库错误!';
                }
            }
        }

        return true;
    }

    /**
     * 安装数据
     *
     * @return bool|array|string
     * @author zero
     */
    public function install(): bool|array|string
    {
        $err = $this->checkDB();
        if ($err !== true) {
            return $err;
        }

        try {
            if (!$this->createTable($this->mysqlVersion())) {
                return '创建数据表失败';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return $this->successTable;
    }
}