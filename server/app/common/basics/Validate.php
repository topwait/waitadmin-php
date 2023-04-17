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

namespace app\common\basics;

use app\common\enums\ErrorEnum;
use JetBrains\PhpStorm\Pure;
use think\exception\HttpResponseException;
use think\Response;

/**
 * 验证器基类
 */
class Validate extends \think\Validate
{
    /**
     * 切面验证接收到的参数
     *
     * @author zero
     * @param string $scene
     * @param array $data (扩展的验证参数)
     * @return void
     */
    public function goCheck(string $scene='', array $data=[])
    {
        $params = request()->param();
        if (!empty($data)) {
            $params = array_merge($params, $data);
        }

        if (!($scene ? $this->scene($scene)->check($params) : $this->check($params))) {
            $exception = is_array($this->error) ? implode(';', $this->error) : $this->error;
            $data = array('code'=>ErrorEnum::PARAMS_ERROR, 'msg'=>$exception, 'data'=>[]);
            $response = Response::create($data, 'json');
            throw new HttpResponseException($response);
        }
    }

    /**
     * 主键场景验证器
     *
     * @author zero
     * @param array $data
     * @return void
     */
    public function idCheck(array $data=[])
    {
        $this->only(['id']);
        $this->goCheck('', $data);
    }

    /**
     * 批量场景验证器
     *
     * @author zero
     * @param array $data
     * @return void
     */
    public function idsCheck(array $data=[])
    {
        $this->only(['ids']);
        $this->goCheck('', $data);
    }

    /**
     * 新增场景验证器
     *
     * @author zero
     * @param array $data
     * @return void
     */
    public function addCheck(array $data=[])
    {
        $this->remove('id', 'require');
        $this->remove('ids', 'require');
        $this->goCheck('', $data);
    }

    /**
     * 编辑场景验证器
     *
     * @param array $data
     * @author zero
     */
    public function editCheck(array $data=[])
    {
        $this->remove('ids', 'require');
        $this->goCheck('', $data);
    }

    /**
     * 验证正整数规则
     *
     * @param $value(需验证的值)
     * @return bool
     * @author zero
     */
    #[Pure]
    protected function posInteger($value): bool
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return false;
    }

    /**
     * 验证最小值规则
     *
     * @param $value (需验证的值)
     * @param $rule (规则值)
     * @return bool
     * @author zero
     */
    protected function minValue($value, $rule): bool
    {
        if ($value < $rule) {
            return false;
        }

        return true;
    }

    /**
     * 验证最大值规则
     *
     * @param $value (需验证的值)
     * @param $rule (规则值)
     * @return bool
     * @author zero
     */
    protected function maxValue($value, $rule): bool
    {
        if ($value > $rule) {
            return false;
        }

        return true;
    }
}