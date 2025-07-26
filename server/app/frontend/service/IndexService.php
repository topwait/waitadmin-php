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

namespace app\frontend\service;

use app\common\basics\Service;
use app\common\model\dev\DevBanner;
use app\common\model\dev\DevLinks;

/**
 * 主页服务类
 */
class IndexService extends Service
{
    /**
     * 获取轮播图
     *
     * @param int $position
     * @return array
     * @author zero
     */
    public static function getBanner(int $position): array
    {
        $model = new DevBanner();
        return $model->field('id,title,image,target,url')
            ->where(['position'=>$position])
            ->where(['is_disable'=>0])
            ->where(['is_delete'=>0])
            ->order('sort desc, id desc')
            ->select()
            ->toArray();
    }

    /**
     * 获取友情链接
     *
     * @return array
     * @author zero
     */
    public static function getLinks(): array
    {
        $model = new DevLinks();
        return $model->field('id,title,target,url')
            ->where(['is_disable'=>0])
            ->where(['is_delete'=>0])
            ->order('sort desc, id desc')
            ->select()
            ->toArray();
    }
}