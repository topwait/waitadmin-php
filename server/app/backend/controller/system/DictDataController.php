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

use app\backend\service\system\DictDataService;
use app\backend\validate\PageValidate;
use app\backend\validate\system\DictDataValidate;
use app\common\basics\Backend;
use app\common\exception\OperateException;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 字典数据管理
 */
class DictDataController extends Backend
{
    /**
     * 字典数据列表
     *
     * @return View|Json
     * @throws DbException
     * @author zero
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            (new PageValidate())->goCheck();
            $lists = DictDataService::lists($this->request->get());
            return AjaxUtils::success($lists);
        }

        return view('system/dict/dataList', [
            'typeId' => intval($this->request->get('type_id'))
        ]);
    }

    /**
     * 字典数据新增
     *
     * @return Json|View
     * @throws OperateException
     * @author zero
     */
    public function add(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new DictDataValidate())->addCheck();
            DictDataService::add($this->request->post());
            return AjaxUtils::success();
        }

        return view('system/dict/dataAdd');
    }

    /**
     * 字典数据编辑
     *
     * @return Json|View
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws OperateException
     * @author zero
     */
    public function edit(): View|Json
    {
        if ($this->isAjaxPost()) {
            (new DictDataValidate())->editCheck();
            DictDataService::edit($this->request->post());
            return AjaxUtils::success();
        }

        (new DictDataValidate())->idCheck();
        $id = intval($this->request->get('id'));

        return view('system/dict/dataEdit', [
            'detail' => DictDataService::detail($id)
        ]);
    }

    /**
     * 字典数据删除
     *
     * @return Json
     * @author zero
     */
    public function del(): Json
    {
        if ($this->isAjaxPost()) {
            (new DictDataValidate())->idsCheck();
            DictDataService::del($this->request->post('ids'));
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}