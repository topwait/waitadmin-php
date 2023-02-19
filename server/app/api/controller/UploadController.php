<?php

namespace app\api\controller;

use app\api\service\UploadService;
use app\common\basics\Api;
use app\common\utils\AjaxUtils;
use think\response\Json;

class UploadController extends Api
{
    /**
     * 上传图片
     *
     * @return Json
     * @author windy
     */
    public function image(): Json
    {
        $result = UploadService::image();
        return AjaxUtils::success($result);
    }

    /**
     * 上传视频
     *
     * @return Json
     * @author windy
     */
    public function video(): Json
    {
        $result = UploadService::image();
        return AjaxUtils::success($result);
    }
}