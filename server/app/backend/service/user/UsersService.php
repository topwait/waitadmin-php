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

namespace app\backend\service\user;

use app\common\basics\Service;
use app\common\enums\GenderEnum;
use app\common\model\user\User;
use app\common\utils\UrlUtils;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 用户管理服务类
 */
class UsersService extends Service
{
    /**
     * 用户列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author zero
     */
    public static function lists(array $get): array
    {
        self::setSearch([
            '='        => ['gid@u.group_id'],
            'datetime' => ['datetime@u.create_time'],
            'keyword'  => [
                'sn'       => ['%like%', 'sn'],
                'nickname' => ['%like%', 'nickname'],
                'mobile'   => ['=', 'mobile']
            ]
        ]);

        $model = new User();
        $lists = $model->alias('u')
            ->field([
                'ug.name as groups',
                'u.id,u.sn,u.avatar,u.account,u.nickname',
                'u.mobile,u.email,u.gender,u.is_disable,u.create_time'
            ])
            ->leftJoin('user_group ug', 'ug.id=u.group_id')
            ->where(self::$searchWhere)
            ->where(['u.is_delete'=>0])
            ->order('u.id desc')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page'  => 'page'
            ])->toArray();

        foreach ($lists['data'] as &$item) {
            $item['avatar'] = UrlUtils::toAbsoluteUrl($item['avatar']);
            $item['gender'] = GenderEnum::getMsgByCode($item['gender']);
            $item['email']  = $item['email'] ?: '-';
            $item['groups'] = $item['groups'] ?: '-';
        }

        return ['count'=>$lists['total'], 'list'=>$lists['data']] ?? [];
    }

    /**
     * 用户详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function detail(int $id): array
    {
        $model = new User();
        $detail = $model->field(true)
            ->where(['id'=> $id])
            ->where(['is_delete'=>0])
            ->findOrFail()
            ->toArray();

        $detail['avatar'] = UrlUtils::toAbsoluteUrl($detail['avatar']);
        $detail['gender'] = GenderEnum::getMsgByCode($detail['gender']);
        return $detail;
    }

    /**
     * 用户分组
     *
     * @param array $ids (用户IDS)
     * @param int $gid   (分组ID)
     * @author zero
     */
    public static function setGroup(array $ids, int $gid): void
    {
        User::update([
            'group_id'    => $gid,
            'update_time' => time()
        ], [['id', 'in', $ids]]);
    }
}