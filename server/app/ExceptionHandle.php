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

namespace app;

use app\common\enums\ErrorEnum;
use app\common\exception\BaseException;
use app\common\model\sys\SysLog;
use app\common\utils\AjaxUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Request;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    /**
     * 错误编码
     * @var int
     */
    private int $errCode = 0;

    /**
     * 错误信息
     * @var string
     */
    private string $errMsg = '';

    /**
     * 错误数据
     * @var array
     */
    private array $errData = [];

    /**
     * 不需要记录信息(日志)的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息 (包括日志或者其它方式记录)
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);

        // 使用自定的方式记录异常日志
        if ($this->app->http->getName() === 'backend') {
            if (request()->isAjax()) {
                $adminId  = session('adminUser')['id'] ?? 0;
                $entrance = config('project.backend_entrance');
                $baseUrls = str_replace($entrance, '', request()->baseUrl());
                $log = SysLog::create([
                    'admin_id'    => $adminId,
                    'method'      => request()->method(),
                    'url'         => str_replace('.html', '', $baseUrls),
                    'ip'          => request()->ip(),
                    'ua'          => request()->header('User-Agent'),
                    'params'      => json_encode(request()->param(), JSON_UNESCAPED_UNICODE),
                    'error'       => $exception->getMessage(),
                    'trace'       => $exception->getTraceAsString(),
                    'start_time'  => round($this->app->getBeginTime(), 3),
                    'status'      => 2,
                    'create_time' => time()
                ]);
                SysLog::$logId = intval($log['id']);
            }
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 自定义的异常处理机制
        if ($e instanceof BaseException) {
            $this->errMsg  = $e->getMessage();
            $this->errCode = $e->getCode();
            $this->errData = $e->data;
        } elseif ($e instanceof ModelNotFoundException) {
            $this->errCode = ErrorEnum::FOUNDER_ERROR;
            $this->errMsg  = ErrorEnum::getMsgByCode($this->errCode);
        } elseif ($e instanceof HttpResponseException) {
            if (is_array($e->getResponse()->getData())) {
                $this->errMsg  = $e->getResponse()->getData()['msg'];
                $this->errCode = $e->getResponse()->getData()['code'];
                $this->errData = $e->getResponse()->getData()['data'];
            }
        }

        // 自定义的异常处理抛出
        if ($this->errCode) {
            if ($request->isAjax() || $this->app->http->getName() === 'api') {
                return AjaxUtils::error(
                    $this->errMsg,
                    $this->errData,
                    $this->errCode,
                );
            } else {
                session('error', json_encode(['errCode'=>$this->errCode, 'errMsg'=>$this->errMsg]));
                return redirect(route('error/wrong'));
            }
        }

        // 其他错误交给系统处理
        if ($request->isAjax() || $this->app->http->getName() === 'api') {
            $this->errCode = ErrorEnum::SYSTEM_ERROR;
            return AjaxUtils::error(
                $e->getMessage(),
                $this->errData,
                $this->errCode,
            );
        }

        return parent::render($request, $e);
    }
}
