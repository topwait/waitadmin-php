<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/waitadmin-php
// | github:  https://github.com/topwait/waitadmin-php
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\common\basics;

use app\common\model\sys\SysConfig;
use Exception;

/**
 * 服务类基类
 */
class Service
{
    /**
     * 模型实例
     * @var object
     */
    private static object $model;

    /**
     * 错误信息
     * @var string
     */
    protected static string $error;

    /**
     * 返回状态码
     * @var int
     */
    protected static int $returnCode = 1;

    /**
     * 搜索条件
     * @var array
     */
    protected static array $searchWhere = [];

    /**
     * 返回错误信息
     *
     * @author zero
     * @return string
     */
    public static function getError(): string
    {
        return self::$error ?? 'Unknown';
    }

    /**
     * 返回指定状态码
     *
     * @author zero
     * @return int
     */
    public static function getReturnCode(): int
    {
        return self::$returnCode;
    }

    /**
     * 事务开启
     *
     * @author zero
     */
    protected static function dbStartTrans(): void
    {
        self::$model = new SysConfig();
        self::$model->startTrans();
    }

    /**
     * 事务提交
     *
     * @author zero
     */
    protected static function dbCommit(): void
    {
        self::$model->commit();
    }

    /**
     * 事务回滚
     *
     * @author zero
     */
    protected static function dbRollback(): void
    {
        self::$model->rollback();
    }

    /**
     * 设置搜索条件
     * PS: 参数名@字段名: user@U.sn
     *
     * @param array $search
     * @return array
     * @author zero
     */
    protected static function setSearch(array $search): array
    {
        $params = request()->param();
        if (empty($search)) {
            return [];
        }

        $where = [];
        foreach ($search as $whereType => $whereFields) {
            switch ($whereType) {
                case '=':
                case '<>':
                case '>':
                case '>=':
                case '<':
                case '<=':
                case 'in':
                    foreach ($whereFields as $whereField) {
                        $paramsName = strpos($whereField, '@') ? explode('@', $whereField) : $whereField;
                        $key   = is_array($paramsName) ? $paramsName[0] : $paramsName; //参数的名称
                        $field = is_array($paramsName) ? $paramsName[1] : $whereField; //字段的名称
                        try {
                            if (!isset($params[$key]) || (!is_numeric($params[$key]) && !$params[$key])) {
                                continue;
                            }
                            $where[] = [$field, $whereType, $params[$key]];
                        } catch (Exception) {}
                    }
                    break;
                case '%like%':
                    foreach ($whereFields as $whereField) {
                        $paramsName = strpos($whereField, '@') ? explode('@', $whereField) : $whereField;
                        $key = is_array($paramsName) ? $paramsName[0] : $paramsName;
                        $val = is_array($paramsName) ? $paramsName[1] : $whereField;
                        try {
                            if (!isset($params[$key]) || (!is_numeric($params[$key]) && !$params[$key])) {
                                continue;
                            }
                            $where[] = [$val, 'like', '%' . $params[$key] . '%'];
                        } catch (Exception) {}
                    }
                    break;
                case '%like':
                    foreach ($whereFields as $whereField) {
                        $paramsName = strpos($whereField, '@') ? explode('@', $whereField) : $whereField;
                        $key = is_array($paramsName) ? $paramsName[0] : $paramsName;
                        $val = is_array($paramsName) ? $paramsName[1] : $whereField;
                        try {
                            if (!isset($params[$key]) || (!is_numeric($params[$key]) && !$params[$key])) {
                                continue;
                            }
                            $where[] = [$val, 'like', '%' . $params[$key]];
                        } catch (Exception) {}
                    }
                    break;
                case 'like%':
                    foreach ($whereFields as $whereField) {
                        $paramsName = strpos($whereField, '@') ? explode('@', $whereField) : $whereField;
                        $key = is_array($paramsName) ? $paramsName[0] : $paramsName;
                        $val = is_array($paramsName) ? $paramsName[1] : $whereField;
                        try {
                            if (!isset($params[$key]) || (!is_numeric($params[$key]) && !$params[$key])) {
                                continue;
                            }
                            $where[] = [$val, 'like', $params[$key] . '%'];
                        } catch (Exception) {}
                    }
                    break;
                case 'between':
                    foreach ($whereFields as $whereField) {
                        $arrayInput = explode('@', $whereField);
                        $keyArg = $arrayInput[0]??'';
                        $dbName = $arrayInput[1]??'';
                        $paramsName = explode('|', $keyArg);
                        if (empty($params[$paramsName[0]]) || empty($params[$paramsName[1]])) {
                            break;
                        }
                        $start = $params[$paramsName[0]];
                        $end   = $params[$paramsName[1]];
                        $where[] = [$dbName, 'between', [$start, $end]];
                    }
                    break;
                case 'datetime':
                    foreach ($whereFields as $whereField) {
                        $paramsName = !strpos($whereField, '@') ? $whereField : explode('@', $whereField);
                        $key = is_array($paramsName) ? $paramsName[0] : $paramsName;
                        $val = is_array($paramsName) ? $paramsName[1] : $whereField;
                        if (!isset($params[$key]) || !$params[$key]) {
                            continue;
                        }

                        if (str_contains($params[$key], ' - ')) {
                            list($start, $end) = explode(' - ', $params[$key]);
                            $where[] = [$val, '>=', strtotime($start)];
                            $where[] = [$val, '<=', strtotime($end)];
                        } else {
                            $where[] = [$val, '>=', strtotime($params[$key])];
                        }
                    }
                    break;
                case 'keyword':
                    if (!isset($params['keyword_type']) || empty($params['keyword'])) {
                        break;
                    }
                    $value = $whereFields[$params['keyword_type']];
                    $where[] = match ($value[0]) {
                        '=', '<>', '>', '>=', '<', '<=', 'in' => [$value[1], $value[0], $params['keyword']],
                        '%like%' => [$value[1], 'like', '%' . $params['keyword'] . '%'],
                        '%like'  => [$value[1], 'like', '%' . $params['keyword']],
                        'like%'  => [$value[1], 'like', $params['keyword'] . '%'],
                    };
                    break;
            }
        }
        self::$searchWhere = $where;
        return $where;
    }
}