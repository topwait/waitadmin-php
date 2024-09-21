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

namespace app\backend\controller\system;

use app\backend\service\system\DictTypeService;
use app\backend\validate\PageValidate;
use app\backend\validate\system\DictTypeValidate;
use app\common\basics\Backend;
use app\common\exception\OperateException;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 字典类型管理
 */
class DictTypeController extends Backend
{
    /**
     * 字典类型列表
     *
     * @return Json|View
     * @throws DbException
     * @author zero
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            (new PageValidate())->goCheck();
            $lists = DictTypeService::lists($this->request->get());
            return AjaxUtils::success($lists);
        }

        return view('system/dict/typeList');
    }

    /**
     * 字典类型新增
     *
     * @return View|Json
     * @throws OperateException
     * @author zero
     */
    public function add(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new DictTypeValidate())->addCheck();
            DictTypeService::add($this->request->post());
            return AjaxUtils::success();
        }

        return view('system/dict/typeAdd');
    }

    /**
     * 字典类型编辑
     *
     * @return View|Json
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws OperateException
     * @author zero
     */
    public function edit(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new DictTypeValidate())->editCheck();
            DictTypeService::edit($this->request->post());
            return AjaxUtils::success();
        }

        (new DictTypeValidate())->idCheck();
        $id = intval($this->request->get('id'));

        return view('system/dict/typeEdit', [
            'detail' => DictTypeService::detail($id)
        ]);
    }

    /**
     * 字典类型删除
     *
     * @return Json
     * @author zero
     */
    public function del(): Json
    {
        if ($this->isAjaxPost()) {
            (new DictTypeValidate())->idsCheck();
            DictTypeService::del($this->request->post('ids'));
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}