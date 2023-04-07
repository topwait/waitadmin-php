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

namespace app\backend\controller;

use app\backend\service\AttachService;
use app\backend\validate\AttachValidate;
use app\common\basics\Backend;
use app\common\exception\OperateException;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\Json;
use think\response\View;

/**
 * 附件管理
 *
 * Class AttachController
 * @package app\admin\controller
 */
class AttachController extends Backend
{
    /**
     * 附件列表
     *
     * @return Json|View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @method [GET]
     * @author zero
     */
    public function index(): View|Json
    {
        if ($this->isAjaxGet()) {
            (new AttachValidate())->goCheck('list');
            $list = AttachService::lists($this->request->get());
            return AjaxUtils::success($list);
        }

        return view('common/attach', [
            'type'       => $this->request->get('type'),
            'category'   => AttachService::cateLists(),
            'imageLimit' => config('project.uploader.image'),
            'videoLimit' => config('project.uploader.video')
        ]);
    }

    /**
     * 附件命名
     *
     * @return Json
     * @method [POST]
     * @author zero
     */
    public function rename(): Json
    {
        if ($this->isAjaxPost()) {
            (new AttachValidate())->goCheck('rename');
            AttachService::rename($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }

    /**
     * 附件移动
     *
     * @return Json
     * @method [POST]
     * @author zero
     */
    public function move(): Json
    {
        if ($this->isAjaxPost()) {
            (new AttachValidate())->goCheck('move');
            AttachService::move($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }

    /**
     * 附件删除
     *
     * @return Json
     * @method [POST]
     * @author zero
     */
    public function del(): Json
    {
        if ($this->isAjaxPost()) {
            (new AttachValidate())->goCheck('del');
            AttachService::del($this->request->post('ids'));
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }

    /**
     * 分组列表
     *
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @method [GET]
     * @author zero
     */
    public function cateLists(): Json
    {
        if ($this->isAjaxGet()) {
            $list = AttachService::cateLists();
            return AjaxUtils::success($list);
        }

        return AjaxUtils::error();
    }

    /**
     * 分组创建
     *
     * @return Json
     * @method [POST]
     * @author zero
     */
    public function cateAdd(): Json
    {
        if ($this->isAjaxPost()) {
            (new AttachValidate())->goCheck('cateCreate');
            AttachService::cateCreate($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }

    /**
     * 分组命名
     *
     * @return Json
     * @throws OperateException
     * @method [POST]
     * @author zero
     */
    public function cateRename(): Json
    {
        if ($this->isAjaxPost()) {
            (new AttachValidate())->goCheck('cateRename');
            AttachService::cateRename($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }

    /**
     * 分组删除
     *
     * @return Json
     * @throws OperateException
     * @method [POST]
     * @author zero
     */
    public function cateDelete(): Json
    {
        if ($this->isAjaxPost()) {
            (new AttachValidate())->goCheck('cateDelete');
            AttachService::cateDelete($this->request->post());
            return AjaxUtils::success();
        }

        return AjaxUtils::error();
    }
}