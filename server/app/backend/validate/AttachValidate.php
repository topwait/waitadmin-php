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

namespace app\backend\validate;


use app\common\basics\Validate;

/**
 * 附件参数验证器
 *
 * Class AttachValidate
 * @package app\backend\validate
 */
class AttachValidate extends Validate
{
    protected $rule = [
        'id'    => 'require|posInteger',
        'ids'   => 'require|array',
        'cid'   => 'require|integer',
        'type'  => 'require|in:10,20',
        'name'  => 'require|max:20|min:1|unique:attachCate',
        'title' => 'require|max:200|min:1'
    ];

    public function __construct()
    {
        $this->field = [
            'cid'   => '所属分组',
            'type'  => '附件类型',
            'name'  => '分组名称',
            'title' => '文件名称',
        ];

        parent::__construct();
    }

    /**
     * 附件列表场景
     *
     * @author windy
     * @return AttachValidate
     */
    public function sceneList()
    {
        return $this->only(['cid', 'type'])
            ->remove('cid', 'require');
    }

    /**
     * 附件移动场景
     *
     * @author windy
     * @return AttachValidate
     */
    public function sceneMove()
    {
        return $this->only(['ids', 'cid', 'type']);
    }

    /**
     * 附件命名场景
     *
     * @author windy
     * @return AttachValidate
     */
    public function sceneRename()
    {
        return $this->only(['id', 'type', 'title']);
    }

    /**
     * 附件删除场景
     *
     * @author windy
     * @return AttachValidate
     */
    public function sceneDel()
    {
        return $this->only(['ids', 'type']);
    }

    /**
     * 分组创建场景
     *
     * @author windy
     * @return AttachValidate
     */
    public function sceneCateCreate()
    {
        return $this->only(['type', 'name']);
    }

    /**
     * 分组命名场景
     *
     * @author windy
     * @return AttachValidate
     */
    public function sceneCateRename()
    {
        return $this->only(['id', 'type', 'name']);
    }

    /**
     * 分组删除场景
     *
     * @author windy
     * @return AttachValidate
     */
    public function sceneCateDelete()
    {
        return $this->only(['id', 'type']);
    }
}