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


use app\backend\service\UploadService;
use app\common\basics\Backend;
use app\common\exception\UploadException;
use app\common\utils\AjaxUtils;
use think\response\Json;

/**
 * 上传管理
 */
class UploadController extends Backend
{
    /**
     * 附件上传
     *
     * @return Json
     * @throws UploadException
     * @method [POST]
     * @author zero
     */
    public function attach(): Json
    {
        $type = $this->request->get('type');
        $cid  = $this->request->post('cid', 0);

        $path = 'attach/'.$type.'/';
        $result = UploadService::storage($type, $path, intval($cid), $this->adminId);
        return AjaxUtils::success('上传成功', $result);
    }

    /**
     * 临时上传
     *
     * @return Json
     * @throws UploadException
     * @method [POST]
     * @author zero
     */
    public function temporary(): Json
    {
        $type = $this->request->get('type');
        $result = UploadService::temporary($type);
        return AjaxUtils::success('上传成功', $result);
    }
}