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

namespace app\backend\controller;

use app\common\basics\Backend;
use app\backend\service\OfficialService;
use EasyWeChat\Kernel\Exceptions\BadRequestException;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use ReflectionException;
use think\Response;
use Throwable;

/**
 * 公众号响应管理
 */
class OfficialController extends Backend
{
    protected array $notNeedLogin = ['reply'];

    /**
     * 微信公众号回调应答
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws BadRequestException
     * @throws RuntimeException
     * @throws ReflectionException
     * @throws Throwable
     * @method [GET|POST]
     * @author loneboat
     */
    public function reply(): Response
    {
        $result = OfficialService::reply();
        return response($result->getBody())->header([
            'Content-Type' => 'text/plain;charset=utf-8'
        ]);
    }
}