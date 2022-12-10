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

namespace app\backend\validate\setting;


use app\common\basics\Validate;

/**
 * 轮播图参数验证器
 *
 * Class BannerValidate
 * @package app\backend\validate\setting
 */
class BannerValidate extends Validate
{
    protected $rule = [
        'id'         => 'require|posInteger',
        'ids'        => 'require|array',
        'position'   => 'require|integer',
        'title'      => 'require|max:200',
        'image'      => 'require|max:250',
        'target'     => 'require|in:_self,_blank,_parent,_top',
        'url'        => 'max:250',
        'sort'       => 'number',
        'is_disable' => 'require|in:0,1',
    ];

    public function __construct()
    {
        $this->field = [
            'position'   => '轮播位置',
            'title'      => '轮播标题',
            'image'      => '轮播图片',
            'target'     => '跳转方式',
            'url'        => '跳转链接',
            'sort'       => '排序编号',
            'is_disable' => '轮播状态'
        ];

        parent::__construct();
    }
}