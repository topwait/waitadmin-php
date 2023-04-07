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

namespace app\api\controller;

use app\api\service\UploadService;
use app\common\exception\UploadException;
use app\common\basics\Api;
use app\common\utils\AjaxUtils;
use think\response\Json;

/**
 * 上传管理
 */
class UploadController extends Api
{
    /**
     * 上传文件
     *
     * @return Json
     * @throws UploadException
     * @method [POST]
     * @author zero
     */
    public function file(): Json
    {
        $type = $this->request->post('type');
        $dir  = $this->request->post('dir');

        if ($dir === 'temporary') {
            $result = UploadService::temporary($type);
        } else {
            $result = UploadService::storage($type, $dir);
        }

        return AjaxUtils::success($result);
    }
}