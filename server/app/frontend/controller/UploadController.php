<?php

namespace app\frontend\controller;


use app\common\basics\Frontend;
use app\common\exception\UploadException;
use app\common\utils\AjaxUtils;
use app\frontend\service\UploadService;
use think\response\Json;

class UploadController extends Frontend
{
    /**
     * 临时商城
     *
     * @return Json
     * @throws UploadException
     * @author windy
     */
    public function temporary(): Json
    {
        $type = $this->request->post('type');
        $result = UploadService::temporary($type);
        return AjaxUtils::success('上传成功', $result);
    }
}