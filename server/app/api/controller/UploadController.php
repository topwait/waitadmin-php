<?php

namespace app\api\controller;

use app\api\service\UploadService;
use app\common\basics\Api;
use app\common\exception\UploadException;
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
     * @author windy
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