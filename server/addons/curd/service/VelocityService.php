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
// | Author: zero <2474369941@qq.com>
// +----------------------------------------------------------------------

namespace addons\curd\service;

use app\common\basics\Service;
use think\facade\Db;

/**
 * 生成处理服务类
 */
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
     * @author zero
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
     * @author zero
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
     * @param array $table    (表信息)
     * @param array $columns  (列信息)
     * @param array $dictList (字典列表)
     * @author zero
     */
    public static function prepareContext(array $table, array $columns, array $dictList): array
    {
        $table['gen_model'] = self::toCamel($table['table_name']);
        $detail = [
            'table'      => $table,
            'columns'    => $columns,
            'dictList'   => $dictList,
            'routes'     => self::makeRoutes($table),
            'namespace'  => '', // 命名空间路径
            'primaryKey' => '', // 主键字段名称
            'fieldsArr'  => [], // 所有字段数组
            'joinLsArr'  => [], // 连表列表字段
            'joinDtArr'  => [], // 连表详情字段
            'searchArr'  => [], // 搜索字段数组
            'searchDict' => [], // 搜索字典数组
            'listIgnore' => [], // 列表忽略数组
            'layImport'  => []  // 前端导入字段
        ];

        foreach ($columns as $column) {
            $detail['fieldsArr'][] = $column['column_name'];

            // 获取出表中唯一的主键
            if ($column['is_pk'] && $column['is_increment']) {
                $detail['primaryKey'] = $column['column_name'];
            }

            // 查找出那些字段需搜索
            if ($column['is_query']) {
                $alias = $table['join_status'] ? $table['table_alias'].'.' : '';
                $detail['searchArr'][$column['query_type']][] = $alias.$column['column_name'];

                $k = [
                    'is_enable'  => [['name'=>'正常', 'value'=>1], ['name'=>'停用', 'value'=>0]],
                    'is_disable' => [['name'=>'启用', 'value'=>0], ['name'=>'禁用', 'value'=>1]],
                    'is_stop'    => [['name'=>'启用', 'value'=>0], ['name'=>'停用', 'value'=>1]],
                    'status'     => [['name'=>'是', 'value'=>1], ['name'=>'否', 'value'=>0]],
                ];

                $allowType = ['select', 'checkbox', 'radio'];
                if ($column['dict_type'] && in_array($column['html_type'], $allowType)) {
                    $detail['searchDict'][$column['column_name']] = [
                        'type' => 'select',
                        'name' => $column['column_comment'],
                        'list' => $dictList[$column['dict_type']]??[],
                    ];
                } elseif (str_starts_with($column['column_name'], 'is_') && in_array($column['column_type'], ['int', 'tinyint'])) {
                    $detail['searchDict'][$column['column_name']] = [
                        'type' => 'select',
                        'name' => $column['column_comment'],
                        'list' => $k[$column['column_name']] ?? $k['status']
                    ];
                } elseif ($column['column_name'] == 'status' && in_array($column['column_type'], ['int', 'tinyint'])) {
                    $detail['searchDict'][$column['column_name']] = [
                        'type' => 'select',
                        'name' => $column['column_comment'],
                        'list' => $k['status']
                    ];
                } elseif ($column['query_type'] == 'datetime') {
                    $detail['searchDict'][$column['column_name']] = [
                        'type' => 'datetime',
                        'name' => $column['column_comment'],
                    ];
                } else {
                    $detail['searchDict'][$column['column_name']] = [
                        'type' => 'input',
                        'name' => $column['column_comment'],
                    ];
                }
            }

            // 普通列表需忽略的字段
            if (!$column['is_list']) {
                $detail['listIgnore'][] = $column['column_name'];
            }

            // 关联列表需显示的字段
            if ($column['is_list'] && $table['join_status']) {
                $detail['joinLsArr'][] = $table['table_alias'].'.'.$column['column_name'];
            }

            // 新增编辑要导入的组件
            if ($column['is_insert'] || $column['is_edit']) {
                if ($column['html_type'] == 'editor' && !in_array('tinymce', $detail['layImport'])) {
                    $detail['layImport'][] = 'tinymce';
                }

                if ($column['html_type'] == 'datetime' && !in_array('laydate', $detail['layImport'])) {
                    $detail['layImport'][] = 'laydate';
                }
            }
        }

        // 处理关联状态下需显示的字段
        if ($table['join_status']) {
            $tableArrays = $table['join_array'];
            foreach ($tableArrays as $tableArray) {
                $joinField = explode(',', $tableArray['join_field']);
                foreach ($joinField as $field) {
                    if (trim($field)) {
                        $detail['joinLsArr'][] = $field;
                    }
                }
            }
        }

        return $detail;
    }

    /**
     * 获取模板列表
     *
     * @param array $table (表信息)
     * @return string[]
     * @author zero
     */
    public static function getTemplates(array $table): array
    {
        return [
            'php_controller' => $table['gen_class'].'Controller.php',
            'php_service'    => $table['gen_class'].'Service.php',
            'php_validate'   => $table['gen_class'].'Validate.php',
            'php_model'      => self::toCamel($table['table_name']).'.php',
            'html_list'      => 'index.html',
            'html_add'       => 'add.html',
            'html_edit'      => 'edit.html',
        ] ?? [];
    }

    /**
     * 获取字段列主键
     *
     * @param array $columns
     * @return string
     * @author zero
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
     * @author zero
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
     * @author zero
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
     * @author zero
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
     * @author zero
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
     * @author zero
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
     * @author zero
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
     * @author zero
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