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

namespace app\backend\validate\content;


use app\common\basics\Validate;

/**
 * 档案参数验证器
 *
 * Class ArchivesValidate
 * @package app\backend\validate\content
 */
class ArticleValidate extends Validate
{
    protected $rule = [
        'id'           => 'require|integer',
        'ids'          => 'require|array',
        'title'        => 'require',
        'image'        => 'max:200',
        'intro'        => 'max:200',
        'sort'         => 'number',
        'is_topping'   => 'require|in:0,1',
        'is_recommend' => 'require|in:0,1',
        'is_show'      => 'require|in:0,1'
    ];

    public function __construct()
    {
        $this->field = [
            'title'        => '标题',
            'image'        => '封面',
            'intro'        => '简介',
            'sort'         => '排序',
            'is_topping'   => '置顶',
            'is_recommend' => '推荐',
            'is_show'      => '状态'
        ];

        parent::__construct();
    }
}