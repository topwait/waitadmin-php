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

namespace app\backend\service;


use app\common\basics\Service;
use app\common\model\auth\AuthAdmin;
use app\common\model\auth\AuthMenu;
use app\common\model\auth\AuthPerm;
use app\common\model\article\Article;
use app\common\utils\ConfigUtils;
use app\common\utils\UrlUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 主页服务类
 */
class IndexService extends Service
{
    /**
     * 主页
     *
     * @param int $adminId
     * @param int $roleId
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function index(int $adminId, int $roleId): array
    {
        $where = [];
        if ($adminId !== 1) {
            $authPermModel = new AuthPerm();
            $menuIds = $authPermModel->where(['role_id'=>$roleId])->column('menu_id');
            $where = [['id', 'in', $menuIds]];
        }

        $authMenuModel = new AuthMenu();
        $detail['menus'] = $authMenuModel
            ->withoutField('is_delete,delete_time')
            ->where($where)
            ->where(['is_menu'=>1])
            ->where(['is_delete'=>0])
            ->where(['is_disable'=>0])
            ->order('sort asc, id asc')
            ->select()
            ->toArray();

        $authAdminModel = new AuthAdmin();
        $detail['adminUser'] = $authAdminModel
            ->field('id,username,avatar')
            ->where(['id'=>$adminId])
            ->findOrEmpty()
            ->toArray();

        $sideLogo = ConfigUtils::get('backend', 'side_logo', '');
        $detail['config'] = [
            'side_logo' => UrlUtils::toAbsoluteUrl($sideLogo)
        ];

        return $detail;
    }

    /**
     * 控制台
     *
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function console(): array
    {
        // 热门文章
        $modelArticle = new Article();
        $detail['article'] = $modelArticle->field('id,title,image,browse')
            ->where(['is_delete'=>0])
            ->order('browse desc, collect desc, id desc')
            ->limit(7)
            ->select()
            ->toArray();

        // 系统版本
        $detail['version'] = config('project.version');

        return $detail;
    }
}