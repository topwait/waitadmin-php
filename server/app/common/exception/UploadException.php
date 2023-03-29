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

namespace app\common\exception;

use app\common\enums\ErrorEnum;
use Throwable;

/**
 * 上传异常类
 */
class UploadException extends BaseException
{
    public function __construct($message = '', $code = 0, $data = [], Throwable $previous = null)
    {
        $this->data     = $data;
        $this->code     = $code    ?: ErrorEnum::UPLOADS_ERROR;
        $this->message  = $message ?: ErrorEnum::getMsgByCode($this->code);
        parent::__construct($this->message, $this->code, $previous);
    }
}