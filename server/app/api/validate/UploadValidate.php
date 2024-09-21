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

namespace app\api\validate;

use app\common\basics\Validate;

/**
 * 上传参数验证器
 */
class UploadValidate extends Validate
{
    protected $rule = [
        'type' => 'require|in:picture,video,document,package'
    ];

    public function __construct()
    {
        $this->field = [
            'type' => '上传类型'
        ];

        parent::__construct();
    }
}