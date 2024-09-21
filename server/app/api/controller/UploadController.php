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

namespace app\api\controller;

use app\api\service\UploadService;
use app\api\validate\UploadValidate;
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
     * 永久存储
     *
     * @return Json
     * @throws UploadException
     * @author zero
     */
    public function permanent(): Json
    {
        (new UploadValidate())->goCheck();
        $type = $this->request->get('type');

        $result = UploadService::permanent($type, $this->userId);
        return AjaxUtils::success('上传成功', $result);
    }

    /**
     * 临时存储
     *
     * @return Json
     * @throws UploadException
     * @method [POST]
     * @author zero
     */
    public function temporary(): Json
    {
        (new UploadValidate())->goCheck();
        $type = $this->request->post('type');

        $result = UploadService::temporary($type);
        return AjaxUtils::success('上传成功', $result);
    }
}