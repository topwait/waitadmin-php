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

namespace app\common\utils;

use app\common\enums\ErrorEnum;
use think\response\Json;

/**
 * 响应工具
 */
class AjaxUtils
{
    /**
     * 规定格式
     *
     * @param int $code (状态码)
     * @param string $msg (提示)
     * @param array $data (返回数据集)
     * @return array
     * @author zero
     */

    private static function result(int $code, string $msg, array $data=[]): array
    {
        return [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ] ?? [];
    }

    /**
     * 响应成功
     *
     * @param array $data (数据集)
     * @param string|array $msg (提示)
     * @param int $code (状态码)
     * @param int $httpCode (Http状态码)
     * @return Json
     * @author zero
     */
    public static function success(string|array $msg='操作成功', array $data=[], int $code=0, int $httpCode=200): Json
    {
        if (is_array($msg) && empty($data)) {
            $data = $msg;
            $msg  = '获取成功';
        }

        $data = self::result($code, $msg, $data);
        return json($data, $httpCode);
    }

    /**
     * 请求错误
     *
     * @param string $msg (提示)
     * @param array $data (数据集)
     * @param int $code   (状态码)
     * @param int $httpCode (Http状态码)
     * @return Json
     * @author zero
     */
    public static function error(string $msg='请求错误', array $data=[], int $code=ErrorEnum::REQUEST_ERROR, int $httpCode=200): Json
    {
        $data = self::result($code, $msg, $data);
        return json($data, $httpCode);
    }
}