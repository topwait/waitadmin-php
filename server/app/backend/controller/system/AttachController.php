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

use app\backend\service\AttachService;
use app\common\basics\Backend;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\response\View;

/**
 * 系统附件
 */
class AttachController extends Backend
{
    /**
     * 附件管理
     *
     * @return View
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @method [GET]
     * @author zero
     */
    public function index(): View
    {
        return view('', [
            'category'   => AttachService::cateLists(),
            'imageLimit' => config('project.uploader.image'),
            'videoLimit' => config('project.uploader.video'),
            'packageLimit'  => config('project.uploader.package'),
            'documentLimit' => config('project.uploader.document')
        ]);
    }
}