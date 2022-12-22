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

namespace addons\curd\controller;


use addons\curd\service\GenerateService;
use addons\curd\service\VelocityService;
use addons\curd\validate\GenValidate;
use app\backend\service\auth\MenuService;
use app\common\basics\Backend;
use app\common\exception\OperateException;
use app\common\exception\SystemException;
use app\common\utils\AjaxUtils;
use app\common\utils\ArrayUtils;
use Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Config;
use think\response\File;
use think\response\Json;
use think\response\View;

/**
 * 代码生成器
 */
class GenController extends Backend
{
    /**
     * 代码生成列表页
     *
     * @return Json|View
     * @throws DbException
     * @author windy
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            $list = GenerateService::lists($this->request->get());
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 获取库中所有表
     *
     * @return Json|View
     * @author windy
     */
    public function tables(): View|Json
    {
        if ($this->isAjaxGet()) {
            $list = GenerateService::tables($this->request->get());
            return AjaxUtils::success($list);
        }

        return view();
    }

    /**
     * 更新数据表结构
     *
     * @return Json|View
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function update(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new GenValidate())->goCheck();
            GenerateService::update($this->request->post());
            return AjaxUtils::success();
        }

        $id = $this->request->get('id');
        $detail = GenerateService::detail($id);
        return view('', [
            'table'   => $detail['table'],
            'columns' => $detail['columns'],
            'primary' => VelocityService::getPrimary($detail['columns']),
            'tables'  => GenerateService::tables([]),
            'menus'   => ArrayUtils::toTreeHtml(MenuService::lists())
        ]);
    }

    /**
     * 销毁数据表结构
     *
     * @return Json
     * @throws SystemException
     * @author windy
     */
    public function destroy(): Json
    {
        if ($this->isAjaxPost()) {
            $post = $this->request->post();
            $this->validate($post, ['ids'=>'require|array']);
            GenerateService::destroy($post['ids']);
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }

    /**
     * 同步数据表结构
     *
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws OperateException
     */
    public function synchrony(): Json
    {
        if ($this->isAjaxPost()) {
            $id = intval($this->request->post('id'));
            GenerateService::synchrony($id);
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }

    /**
     * 导入选择数据表
     *
     * @return Json
     * @throws OperateException
     * @throws SystemException
     */
    public function imports(): Json
    {
        if ($this->isAjaxPost()) {
            $post = $this->request->post();
            $this->validate($post, ['tableNames'=>'require|array']);
            GenerateService::imports($post['tableNames']);
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }

    /**
     * 导出生成的代码
     *
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    public function exports(): Json
    {
        if ($this->isAjaxPost()) {
            $id = intval($this->request->post('id'));
            GenerateService::exports($id);
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }

    /**
     * 下载生成的代码
     *
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    public function download(): File
    {
        $id = intval($this->request->get('id'));
        $path = GenerateService::download($id);
        return download($path, 'aa.zip');
    }

    /**
     * 预览生成的代码
     *
     * @return View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author windy
     */
    public function preview(): View
    {
        $id = intval($this->request->get('id'));
        $detail = GenerateService::preview($id);
        $keys   = array_keys($detail);
        $values = array_values($detail);

        $viewConfig = Config::get('view');
        $viewConfig['view_suffix'] = 'html';
        \think\facade\View::engine('Think')->config($viewConfig);
        return view('', ['keys'=>$keys, 'values'=>$values]);
    }
}