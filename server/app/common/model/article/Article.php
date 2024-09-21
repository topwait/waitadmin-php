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

namespace app\common\model\article;

use app\common\basics\Models;
use app\common\utils\UrlUtils;
use think\model\relation\HasOne;

/**
 * 文章模型
 */
class Article extends Models
{
    // 设置字段信息
    protected $schema = [
        'id'           => 'int',     //主键
        'cid'          => 'int',     //类目
        'title'        => 'string',  //标题
        'image'        => 'string',  //封面
        'intro'        => 'string',  //简介
        'content'      => 'string',  //内容
        'browse'       => 'int',     //浏览
        'collect'      => 'int',     //收藏
        'sort'         => 'int',     //排序
        'is_topping'   => 'int',     //是否置顶: [0=否, 1=是]
        'is_recommend' => 'int',     //是否推荐: [0=否, 1=是]
        'is_show'      => 'int',     //是否显示: [0=否, 1=是]
        'is_delete'    => 'int',     //是否删除: [0=否, 1=是]
        'create_time'  => 'int',     //创建时间
        'update_time'  => 'int',     //更新时间
        'delete_time'  => 'int',     //删除时间
    ];

    /**
     * 单关联
     *
     * @author zero
     * @return HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(ArticleCategory::class, 'id', 'cid')
            ->field('id,name');
    }

    /**
     * 获取器: 处理内容Src
     *
     * @param $value
     * @return string
     * @author zero
     */
    public function getContentAttr($value): string
    {
        return UrlUtils::editorAbsoluteSrc($value);
    }

    /**
     * 修改器: 处理内容Src
     *
     * @param $value
     * @return string
     * @author zero
     */
    public function setContentAttr($value): string
    {
        return UrlUtils::editorRelativeSrc($value);
    }
}