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

use addons\curd\model\CurdTable;
use addons\curd\model\CurdTableColumn;
use app\common\basics\Service;
use app\common\exception\OperateException;
use app\common\exception\SystemException;
use app\common\model\auth\AuthMenu;
use app\common\model\sys\SysDictType;
use Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use ZipArchive;

/**
 * 代码生成服务类
 */
class GenerateService extends Service
{
    /**
     * 获取生成的列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author zero
     */
    public static function lists(array $get): array
    {
        self::setSearch([
            '%like%' => ['table_name', 'table_comment']
        ]);

        $model = new CurdTable();
        $lists = $model->field(true)
            ->where(self::$searchWhere)
            ->order('id desc')
            ->paginate([
                'page' => $get['page'] ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page' => 'page'
            ])->toArray();

        foreach ($lists['data'] as &$item) {
            $item['tpl_type']    = $item['tpl_type']  == 'curd' ? '单表' : '树表';
            $item['gen_type']    = $item['gen_type']  == 'down' ? '下载' : '覆盖';
            $item['menu_type']   = $item['menu_type'] == 'auto' ? '自动' : '手动';
            $item['join_status'] = $item['join_status'] ? '开启' : '关闭';
        }

        return ['count' => $lists['total'], 'list' => $lists['data']] ?? [];
    }

    /**
     * 获取库中所有表
     *
     * @param array $get
     * @return array
     * @author zero
     */
    public static function tables(array $get): array
    {
        $pageNo = $get['page'] ?? 1;
        $pageSize = $get['limit'] ?? 5;

        $sql = 'SHOW TABLE STATUS WHERE 1=1 ';
        if (!empty($get['name'])) {
            $sql .= ' AND name LIKE "%' . $get['name'] . '%"';
        }

        if (!empty($get['comment'])) {
            $sql .= ' AND comment LIKE "%' . $get['comment'] . '%"';
        }

        $tables = app('db')->query($sql);
        $offset = max(0, ($pageNo - 1) * $pageSize);
        $lists = array_map("array_change_key_case", $tables);
        $lists = array_slice($lists, $offset, $pageSize, true);
        $lists = array_values($lists);

        $collection = [];
        foreach ($lists as $item) {
            $collection[] = [
                'name'        => $item['name'] ?? $item['Name'],
                'engine'      => $item['engine'] ?? $item['Engine'],
                'comment'     => $item['comment'] ?? $item['Comment'],
                'create_time' => $item['create_time'] ?? $item['Create_time'],
            ];
        }

        return ['count' => count($tables), 'list' => $collection] ?? [];
    }

    /**
     * 更新数据表结构
     *
     * @param array $post
     * @throws Exception
     * @author zero
     */
    public static function update(array $post): void
    {
        if ($post['menu_type'] == 'auto' && !$post['menu_name']) {
            throw new OperateException('请填写菜单名称');
        }

        $modelTable = new CurdTable();
        $modelTable->startTrans();
        try {
            $time = time();
            CurdTable::update([
                'table_name'    => $post['table_name'],
                'table_comment' => $post['table_comment'],
                'table_alias'   => $post['table_alias']??'',
                'author'        => $post['author']??'',
                'tpl_type'      => $post['tpl_type'],
                'gen_type'      => $post['gen_type'],
                'gen_class'     => $post['gen_class']??'',
                'gen_module'    => $post['gen_module']??'',
                'gen_folder'    => $post['gen_folder']??'',
                'menu_type'     => $post['menu_type'],
                'menu_name'     => $post['menu_name']??'',
                'menu_icon'     => $post['menu_icon']??'',
                'menu_pid'      => $post['menu_pid']??0,
                'join_status'   => $post['join_status']??0,
                'join_array'    => json_encode($post['join']??[]),
                'update_time'   => $time,
            ], ['id'=>intval($post['id'])]);

            foreach ($post['cols']??[] as $item) {
                CurdTableColumn::update([
                    'column_comment' => $item['column_comment'],
                    'model_type'     => $item['model_type'],
                    'query_type'     => $item['query_type'],
                    'html_type'      => $item['html_type']??'',
                    'dict_type'      => $item['dict_type']??'',
                    'is_required'    => $item['is_required']??0,
                    'is_insert'      => $item['is_insert']??0,
                    'is_edit'        => $item['is_edit']??0,
                    'is_list'        => $item['is_list']??0,
                    'is_query'       => $item['is_query']??0,
                    'update_time'    => $time
                ], ['id'=>intval($item['id'])]);
            }
            $modelTable->commit();
        } catch (Exception $e) {
            $modelTable->startTrans();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 获取生成的结构
     *
     * @param int $id
     * @return array
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function detail(int $id): array
    {
        $modelTable = new CurdTable();
        $detail['table'] = $modelTable
            ->field(true)
            ->where(['id'=> $id])
            ->findOrEmpty()
            ->toArray();

        $modelColumn = new CurdTableColumn();
        $detail['columns'] = $modelColumn
            ->field(true)
            ->where(['table_id'=> $id])
            ->select()
            ->toArray();

        if (empty($detail['table']['join_array'])) {
            $detail['table']['join_array'][] = [
                'join_type'    => 'inner',
                'join_field'   => '',
                'join_alias'   => '',
                'sub_table'    => '',
                'primary_key'  => '',
                'foreign_key'  => ''
            ];
        }

        $dictTypeArray = [];
        foreach ($detail['columns'] as $column) {
            $allowType = ['select', 'checkbox', 'radio'];
            if ($column['dict_type'] && in_array($column['html_type'], $allowType)) {
                $dictTypeArray[] = $column['dict_type'];
            }
        }

        if (!empty($dictTypeArray)) {
            $dictResults = (new SysDictType())
                ->field(['id,name,type'])
                ->with(['datas'])
                ->whereIn('type', $dictTypeArray)
                ->where(['is_enable'=>1])
                ->where(['is_delete'=>0])
                ->select()
                ->toArray();

            $data = [];
            foreach ($dictResults as $item) {
                $data[$item['type']] = $item['datas'];
            }
            $detail['dictList'] = $data;
        }

        return $detail;
    }

    /**
     * 同步表结构信息
     *
     * @param int $id
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws OperateException
     * @author zero
     */
    public static function synchrony(int $id): void
    {
        $modelTable  = new CurdTable();
        $modelColumn = new CurdTableColumn();

        // 旧表数据
        $table   = $modelTable->where(['id'=>$id])->findOrEmpty()->toArray();
        $oldCols = $modelColumn->where(['table_id'=>$id])->select()->toArray();
        $colsMap = [];
        foreach ($oldCols as $col) {
            $colsMap[$col['column_name']] = $col;
        }

        // 新表数据
        $columns = VelocityService::queryColumnsByName($table['table_name']);
        if (empty($columns)) {
            throw new OperateException('表结构不存在!');
        }

        // 更新字段
        $updateIds = [];
        foreach ($columns as $column) {
            $lengths = explode('(', $column['type'])[1]??'0)';
            $types   = explode('(', $column['type'])[0];
            $lengths = explode(')', trim($lengths))[0];
            $column['type']   = $types;
            $column['length'] = $lengths;

            $data = [
                'table_id'       => $id,
                'column_name'    => $column['name'],
                'column_comment' => $column['comment'],
                'column_length'  => trim($lengths),
                'column_type'    => trim($types),
                'model_type'     => VelocityService::toPhpType(trim($types)),
                'is_pk'          => $column['primary'] ? 1 : 0,
                'is_increment'   => $column['autoinc'] ? 1 : 0,
                'is_required'    => $column['primary'] ? 0 : (in_array(strtolower($column['name']), VelocityService::$ignoreFields)?0:1),
                'is_insert'      => $column['primary'] ? 0 : (in_array(strtolower($column['name']), VelocityService::$ignoreFields)?0:1),
                'is_edit'        => $column['primary'] ? 0 : (in_array(strtolower($column['name']), VelocityService::$ignoreFields)?0:1),
                'is_list'        => VelocityService::handleList($column),
                'is_query'       => VelocityService::handleQuery($column),
                'query_type'     => VelocityService::handleQueryWhere($column),
                'html_type'      => VelocityService::handleShowType($column),
                'update_time'    => time()
            ];

            if (in_array($column['name'], array_keys($colsMap))) {
                $nid = $colsMap[$column['name']]['id'];
                $updateIds[] = $nid;
                CurdTableColumn::update($data, ['id'=>$nid]);
            } else {
                CurdTableColumn::create($data);
            }
        }

        $deleteIds = [];
        foreach ($oldCols as $col) {
            if (!in_array($col['id'], $updateIds)) {
                $deleteIds[] = $col['id'];
            }
        }

        if (!empty($deleteIds)) {
            CurdTableColumn::destroy($deleteIds);
        }
    }

    /**
     * 删除表结构信息
     *
     * @param array $ids (主键)
     * @throws SystemException
     * @author zero
     */
    public static function destroy(array $ids): void
    {
        $modelTable  = new CurdTable();
        $modelColumn = new CurdTableColumn();
        $modelTable->startTrans();
        try {
            foreach ($ids as $id) {
                $modelTable->where(['id' => intval($id)])->delete();
                $modelColumn->where(['table_id' => intval($id)])->delete();
            }
            $modelTable->commit();
        } catch (Exception $e) {
            $modelTable->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * 导入表结构信息
     *
     * @param array $tableNames (表名称集)
     * @throws OperateException
     * @throws SystemException
     * @author zero
     */
    public static function imports(array $tableNames): void
    {
        $tables = VelocityService::queryTablesByName($tableNames);
        if (!$tables || count($tableNames) != count($tables)) {
            $errMsg = !$tables ? '未找到相关表信息' : '部分表未能找到';
            throw new OperateException($errMsg);
        }

        $modelClass = new CurdTable();
        $modelClass->startTrans();
        try {
            foreach ($tables as $table) {
                // 生成表信息
                $className = VelocityService::underscoreToCamel($table['name']);
                $genTable = CurdTable::create([
                    'table_name'    => $table['name'],
                    'table_engine'  => $table['engine'],
                    'table_comment' => $table['comment'],
                    'table_alias'   => $table['table_alias']   ?? '',
                    'author'        => $table['author']        ?? 'wait',
                    'tpl_type'      => $table['tpl_type_curd'] ?? 'curd',
                    'gen_type'      => $table['gen_type_down'] ?? 'down',
                    'gen_class'     => $table['gen_class']     ?? $className,
                    'gen_module'    => $table['gen_module']    ?? 'backend',
                    'gen_folder'    => $table['gen_folder']    ?? '',
                    'menu_type'     => $table['menu_type']     ?? 'hand',
                    'menu_pid'      => $table['menu_pid']      ?? '',
                    'menu_name'     => $table['menu_name']     ?? '',
                    'menu_icon'     => $table['menu_icon']     ?? '',
                    'join_status'   => $table['join_status']   ?? 0,
                    'join_array'    => json_encode([]),
                    'create_time'   => time(),
                    'update_time'   => time()
                ]);

                // 生成列表信息
                $columns = VelocityService::queryColumnsByName($table['name']);
                foreach ($columns as $column) {
                    $types   = explode('(', $column['type'])[0];
                    $lengths = explode('(', $column['type'])[1]??'0)';
                    $lengths = explode(')', trim($lengths))[0];
                    $column['type']   = $types;
                    $column['length'] = $lengths;

                    CurdTableColumn::create([
                        'table_id'       => $genTable['id'],
                        'column_name'    => $column['name'],
                        'column_comment' => $column['comment'],
                        'column_length'  => trim($lengths),
                        'column_type'    => trim($types),
                        'model_type'     => VelocityService::toPhpType(trim($types)),
                        'is_pk'          => $column['primary'] ? 1 : 0,
                        'is_increment'   => $column['autoinc'] ? 1 : 0,
                        'is_required'    => $column['primary'] ? 0 : (in_array(strtolower($column['name']), VelocityService::$ignoreFields)?0:1),
                        'is_insert'      => $column['primary'] ? 0 : (in_array(strtolower($column['name']), VelocityService::$ignoreFields)?0:1),
                        'is_edit'        => $column['primary'] ? 0 : (in_array(strtolower($column['name']), VelocityService::$ignoreFields)?0:1),
                        'is_list'        => VelocityService::handleList($column),
                        'is_query'       => VelocityService::handleQuery($column),
                        'query_type'     => VelocityService::handleQueryWhere($column),
                        'html_type'      => VelocityService::handleShowType($column),
                        'create_time'    => time(),
                        'update_time'    => time()
                    ]);
                }
            }
            $modelClass->commit();
        } catch (Exception $e) {
            $modelClass->rollback();
            throw new SystemException($e->getMessage());
        }
    }

    /**
     * 导出生成的代码
     *
     * @param int $id
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws OperateException
     * @author zero
     */
    public static function exports(int $id): void
    {
        $tableData = self::detail($id);
        $table    = (array)$tableData['table'];
        $columns  = (array)$tableData['columns'];
        $dictList = (array)($tableData['dictList']??[]);

        $rootPath = str_replace('\\', '/', root_path()).'app/';
        $genPath  = $rootPath . $table['gen_module'] . '/';

        foreach (VelocityService::getTemplates($table) as $k => $v) {
            $vars = VelocityService::prepareContext($table, $columns, $dictList);
            $view = view('tpl/'.$k, $vars);

            $content = $view->getContent();
            $content = str_replace(';#;', ' ', $content);
            $content = str_replace('%%%', '', $content);
            $content = str_replace('>>>', '', $content);
            $content = str_replace('__', '', $content);

            $genFolder = str_replace('\\', '/', $table['gen_folder']);
            $writePath = match ($k) {
                'php_controller' => $genPath  . 'controller'   . $genFolder . '/' . $v,
                'php_service'    => $genPath  . 'service'      . $genFolder . '/' . $v,
                'php_validate'   => $genPath  . 'validate'     . $genFolder . '/' . $v,
                'php_model'      => $rootPath . 'common/model' . $genFolder . '/' . $v,
                'html_list',
                'html_add',
                'html_edit'      => $genPath . 'view' . $genFolder . '/' . VelocityService::camelToUnderscore($table['gen_class']) . '/' . $v
            };

            if (!file_exists(dirname($writePath))) {
                mkdir(dirname($writePath), 0755, true);
            }
            file_put_contents($writePath , $content);
        }

        self::initMenu($table);
    }

    /**
     * 下载生成代码
     *
     * @param int $id
     * @return string
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function download(int $id): string
    {
        $tableData = self::detail($id);
        $table    = (array)$tableData['table'];
        $columns  = (array)$tableData['columns'];
        $dictList = (array)($tableData['dictList']??[]);

        $path = root_path() . 'runtime/generate/WaitAdmin-curd.zip';
        $path = str_replace('\\', '/', $path);
        if (file_exists($path)) {
            @unlink($path);
        }

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE);

        $rootPath = 'app/';
        $genPath  = $rootPath . $table['gen_module'] . '/';
        foreach (VelocityService::getTemplates($table) as $k => $v) {
            $vars = VelocityService::prepareContext($table, $columns, $dictList);
            $view = view('tpl/'.$k, $vars);

            $content = $view->getContent();
            $content = str_replace('>>>', '', $content);
            $content = str_replace(';#;', ' ', $content);
            $content = str_replace('%%%', '', $content);
            $content = str_replace('__', '', $content);

            $genFolder = str_replace('\\', '/', $table['gen_folder']);
            $writePath = match ($k) {
                'php_controller' => $genPath  . 'controller'   . $genFolder . '/' . $v,
                'php_service'    => $genPath  . 'service'      . $genFolder . '/' . $v,
                'php_validate'   => $genPath  . 'validate'     . $genFolder . '/' . $v,
                'php_model'      => $rootPath . 'common/model' . $genFolder . '/' . $v,
                'html_list',
                'html_edit',
                'html_add' => $genPath . 'view' . $genFolder . '/' . VelocityService::camelToUnderscore($table['gen_class']) . '/' . $v
            };

            $zip->addFromString($writePath, $content);
        }

        $zip->close();
        return (string)$path;
    }

    /**
     * 预览生成的模板
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function preview(int $id): array
    {
        $detail = [];
        $tableData = self::detail($id);

        $table    = (array)$tableData['table'];
        $columns  = (array)$tableData['columns'];
        $dictList = (array)($tableData['dictList']??[]);

        foreach (VelocityService::getTemplates($table) as $k => $v) {
            $vars = VelocityService::prepareContext($table, $columns, $dictList);
            $view = view('tpl/'.$k, $vars);

            $content = $view->getContent();
            $content = str_replace('%%%', '', $content);
            $content = str_replace(';#;', ' ', $content);
            $content = str_replace('>>>', '', $content);
            $content = str_replace('__', '', $content);
            $detail[$v] = $content;
        }

        return $detail;
    }

    /**
     * 初始化菜单
     *
     * @param array $table
     * @throws OperateException
     * @author zero
     */
    public static function initMenu(array $table) {
        if ($table['menu_type'] != 'auto') {
            return;
        }

        if (!$table['menu_name']) {
            throw new OperateException('请填写菜单名称');
        }

        $route = lcfirst(VelocityService::makeRoutes($table));
        $modelMenu = new AuthMenu();
        $menu = $modelMenu->field('id')
            ->where(['module' => 'app'])
            ->where(['pid'    => $table['menu_pid']])
            ->where(['title'  => $table['menu_name']])
            ->where(['icon'   => $table['menu_icon']])
            ->where(['perms'  => $route.'/index'])
            ->findOrEmpty();

        if (!$menu->isEmpty()) {
            return;
        }

        $authMenu = AuthMenu::create([
            'module'  => 'app',
            'pid'     => $table['menu_pid'],
            'title'   => $table['menu_name'],
            'icon'    => $table['menu_icon'],
            'perms'   => $table['menu_pid']>0 ? $route.'/index' : '',
            'sort'    => 0,
            'is_menu' => 1
        ]);

        foreach (['index', 'add', 'edit', 'del'] as $item) {
            $title = match ($item) {
                'index' => $table['menu_name'] . '列表',
                'add'   => $table['menu_name'] . '新增',
                'edit'  => $table['menu_name'] . '编辑',
                'del'   => $table['menu_name'] . '删除',
                default => $table['menu_name'],
            };

            $isMenu = 0;
            if ($item === 'index' && $table['menu_pid']==0) {
                $isMenu = 1;
            }

            AuthMenu::create([
                'module'  => 'app',
                'pid'     => $authMenu['id'],
                'title'   => $title,
                'icon'    => '',
                'perms'   => $route.'/'.$item,
                'sort'    => 0,
                'is_menu' => $isMenu
            ]);
        }
    }
}