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

namespace app\backend\validate\system;

use app\common\basics\Validate;

/**
 * 系统缓存清除参数
 */
class ClearValidate extends Validate
{
    protected $rule = [
        'type'    => 'require|array'
    ];

    public function __construct()
    {
        $this->field = [
            'type'    => '缓存类型'
        ];

        parent::__construct();
    }
}