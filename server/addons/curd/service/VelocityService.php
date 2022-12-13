<?php


namespace addons\curd\service;


use app\common\basics\Service;
use JetBrains\PhpStorm\ArrayShape;
use think\facade\Db;

class VelocityService extends Service
{
    /**
     * 忽略字段
     * @var string[]
     */
    public static array $ignoreFields =  ['create_time', 'update_time', 'delete_time', 'is_delete'];

    /**
     * 根据表名查询表信息
     *
     * @param array $tableNames (表名集)
     * @return array
     * @author windy
     */
    public static function queryTablesByName(array $tableNames): array
    {
        $InArray = "(";
        foreach ($tableNames as $k => $tableName) {
            $InArray .= $k > 0 ? ',' : '';
            $InArray .= "'".$tableName."'";
            if ($k >= count($tableNames)-1) {
                $InArray .= ")";
            }
        }

        $tables = Db::query('SHOW TABLE STATUS WHERE name in '.$InArray);
        $collection = [];
        foreach ($tables as $item) {
            $collection[] = [
                'name'        => $item['Name']    ?? $item['name'],
                'engine'      => $item['Engine']  ?? $item['engine'],
                'comment'     => $item['Comment'] ?? $item['comment'],
                'create_time' => $item['Create_time'] ?? $item['create_time'],
            ];
        }

        return $collection;
    }

    /**
     * 根据表名查询表字段
     *
     * @param string $tableName (表名称)
     * @return array
     * @author windy
     */
    public static function queryColumnsByName(string $tableName): array
    {
        $tablePrefix = config('database.connections.mysql.prefix');
        $tableNameMy = str_replace($tablePrefix, '', $tableName);
        return Db::name($tableNameMy)->getFields();
    }

    /**
     * 设置模板变量
     *
     * @param array $table   (表信息)
     * @param array $columns (列信息)
     * @author windy
     */
    public static function prepareContext(array $table, array $columns)
    {
        $table['gen_model'] = self::toCamel($table['table_name']);
        $detail = [
            'table'      => $table,
            'columns'    => $columns,
            'routes'     => self::makeRoutes($table),
            'namespace'  => '',
            'primaryKey' => '',
            'fieldsArr'  => [],
            'searchArr'  => [],
            'listIgnore' => [],
        ];

        foreach ($columns as $column) {
            $detail['fieldsArr'][] = $column['column_name'];

            if ($column['is_pk'] && $column['is_increment']) {
                $detail['primaryKey'] = $column['column_name'];
            }

            if ($column['is_query']) {
                $detail['searchArr'][$column['query_type']][] = $column['column_name'];
            }

            if (!$column['is_list']) {
                $detail['listIgnore'][] = $column['column_name'];
            }
        }

        return $detail;
    }

    /**
     * 获取模板列表
     *
     * @param array $table (表信息)
     * @return string[]
     * @author windy
     */
    #[ArrayShape([
        'php_controller' => "string", 'php_service' => "string",
        'php_validate'   => "string", 'php_model'   => "string",
        'html_list'      => "string"])]
    public static function getTemplates(array $table): array
    {
        return [
            'php_controller'  => $table['gen_class'].'Controller.php',
            'php_service'     => $table['gen_class'].'Service.php',
            'php_validate'    =>  $table['gen_class'].'Validate.php',
            'php_model'       => self::toCamel($table['table_name']).'.php',
            'html_list'     => 'index.html',
            //'html_edit'     => 'edit.html',
        ];
    }

    /**
     * 获取字段列主键
     *
     * @param array $columns
     * @return string
     * @author windy
     */
    public static function getPrimary(array $columns): string
    {
        foreach ($columns as $item) {
            if ($item['is_pk'] && $item['is_increment']) {
                return $item['column_name'];
            }
        }

        return 'id';
    }

    /**
     * 处理列表列
     *
     * @param array $column (列信息)
     * @return int
     * @author windy
     */
    public static function handleList(array $column): int
    {
        if (in_array($column['name'], ['is_delete', 'delete_time'])) {
            return 0;
        } elseif (in_array($column['type'], ['blob', 'text', 'mediumblob', 'mediumtext', 'longblob', 'longtext'])) {
            return 0;
        } elseif ($column['length'] >= 300) {
            return 0;
        }

        return 1;
    }

    /**
     * 处理搜索列表
     *
     * @param array $column (列信息)
     * @return int
     * @author windy
     */
    public static function handleQuery(array $column): int
    {
        if (in_array($column['name'], ['title', 'tags', 'name', 'username', 'account'])) {
            return 1;
        } elseif (str_starts_with($column['name'], 'is_')) {
            return 1;
        }
        return 0;
    }

    /**
     * 处理搜索条件列
     *
     * @param array $column (列信息)
     * @return string
     * @author windy
     */
    public static function handleQueryWhere(array $column): string
    {
        if (in_array($column['name'], ['create_time', 'update_time', 'delete_time'])) {
            return 'datetime';
        } elseif ($column['name'] == 'start_time') {
            return '>=';
        } elseif ($column['name'] == 'end_time') {
            return '<=';
        } elseif (in_array($column['name'], ['title', 'tags', 'name', 'username', 'account'])) {
            return '%like%';
        } elseif (str_starts_with($column['name'], 'is_')) {
            return '=';
        }
        return 'text';
    }

    /**
     * 处理显示类型列
     *
     * @param array $column (列信息)
     * @return string
     * @author windy
     */
    public static function handleShowType(array $column): string
    {
        if (in_array($column['name'], ['create_time', 'update_time', 'delete_time', 'start_time', 'end_time'])) {
            return 'datetime';
        } elseif (str_starts_with($column['name'], 'is_')) {
            return 'radio';
        } elseif (in_array($column['type'], ['float', 'double', 'decimal', 'tinyint', 'smallint', 'mediumint', 'int', 'integer', 'bigint'])) {
            return 'number';
        } elseif (in_array($column['type'], ['date', 'time', 'year', 'datetime', 'timestamp'])) {
            return 'datetime';
        }
        return 'text';
    }

    /**
     * 转PHP数据类型
     *
     * @param string $mysqlType
     * @return string
     * @author windy
     */
    public static function toPhpType(string $mysqlType): string
    {
        $float   = ['float', 'double', 'decimal'];
        $date    = ['date', 'time', 'year', 'datetime', 'timestamp'];
        $integer = ['tinyint', 'smallint', 'mediumint', 'int', 'integer', 'bigint'];
        $string  = ['char', 'varchar', 'tinyblob', 'tinytext', 'blob', 'text', 'mediumblob', 'mediumtext', 'longblob', 'longtext'];
        if (in_array(strtolower($mysqlType), $float)) {
            return strtolower($mysqlType) == 'float' ? 'float' : 'double';
        } elseif (in_array(strtolower($mysqlType), $date)) {
            return 'string';
        } elseif (in_array(strtolower($mysqlType), $integer)) {
            return 'int';
        } elseif (in_array(strtolower($mysqlType), $string)) {
            return 'string';
        }

        return 'string';
    }

    /**
     * 下划线转驼峰
     *
     * @param string $string
     * @return string
     * @author windy
     */
    public static function toCamel(string $string): string
    {
        $prefix = env('database.prefix', '');
        $separator = '_';
        $string = str_replace($prefix, '', $string);
        $string = $separator . str_replace($separator, ' ', strtolower($string));
        return (string)str_replace(' ', '', ucwords(ltrim($string, $separator)));
    }


    /**
     * 生成请求路由
     *
     * @param array $table (表信息)
     * @return string
     * @author windy
     */
    public static function makeRoutes(array $table): string
    {
        $genFolder = trim($table['gen_folder'], '\\');
        $genFolder = str_replace('\\', '.', $genFolder);
        if ($genFolder) {
            return $genFolder.'.'.$table['gen_class'];
        }
        return $table['gen_class'];
    }
}