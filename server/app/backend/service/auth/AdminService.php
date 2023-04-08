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

namespace app\backend\service\auth;

use app\common\basics\Service;
use app\common\exception\NotAuthException;
use app\common\exception\OperateException;
use app\common\model\auth\AuthAdmin;
use app\common\utils\AttachUtils;
use app\common\utils\FileUtils;
use app\common\utils\UrlUtils;
use JetBrains\PhpStorm\ArrayShape;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 管理员服务类
 */
class AdminService extends Service
{
    /**
     * 管理员列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author zero
     */
    #[ArrayShape(['count' => "int", 'list' => "array"])]
    public static function lists(array $get): array
    {
        self::setSearch([
            '='      => ['phone', 'status@is_disable'],
            '%like%' => ['username'],
        ]);

        $model = new AuthAdmin();
        $lists = $model
            ->withoutField('salt,password,is_delete,delete_time,update_time')
            ->with(['role', 'dept', 'post'])
            ->where(self::$searchWhere)
            ->where(['is_delete'=>0])
            ->order('id asc')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page'  => 'page'
            ])->toArray();

        foreach ($lists['data'] as &$item) {
            if ($item['id'] === 1) {
                $item['role'] = ['id'=>0, 'name'=>'系统管理员'];
            }

            $item['dept'] = $item['dept']['name'] ?? '-';
            $item['post'] = $item['post']['name'] ?? '-';

            unset($item['role_id']);
            unset($item['dept_id']);
            unset($item['post_id']);
            $item['last_login_ip']   = $item['last_login_ip'] ?: '-';
            $item['last_login_time'] = $item['last_login_time'] ? date('Y-m-d H:i:s', $item['last_login_time']) : '-';
        }

        return ['count'=>$lists['total'], 'list'=>$lists['data']];
    }

    /**
     * 管理员详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author zero
     */
    public static function detail(int $id): array
    {
        $model = new AuthAdmin();
        $detail = $model
            ->withoutField('salt,password,is_delete,delete_time,update_time')
            ->where(['id'=>intval($id)])
            ->where(['is_delete'=>0])
            ->findOrFail()
            ->toArray();

        $detail['avatar'] = UrlUtils::toAbsoluteUrl($detail['avatar']);
        return $detail;
    }

    /**
     * 管理员信息
     *
     * @param array $post
     * @param int $adminId
     * @author zero
     */
    public static function info(array $post, int $adminId): void
    {
        $model = new AuthAdmin();
        $admin = $model->field('id,avatar,salt,password')
            ->where(['id'=>intval($post['id'])])
            ->findOrEmpty()
            ->toArray();

        $salt = make_rand_char(6);
        $post['password'] = make_md5_str($post['password'].$salt);
        if (!empty($post['password']) and $post['password']) {
            $post['password'] = $admin['password'];
            $salt = $admin['salt'];
        }

        $avatar = UrlUtils::toRelativeUrl($post['avatar']??'');
        if (empty($post['avatar'])) {
            $avatar = '/static/backend/images/default/avatar.png';
        }

        AttachUtils::markUpdate($admin, $post, ['avatar']);
        AuthAdmin::update([
            'salt'            => $salt,
            'avatar'          => $avatar,
            'nickname'        => $post['nickname'],
            'password'        => $post['password'],
            'phone'           => $post['phone'] ?? '',
            'email'           => $post['email'] ?? '',
            'is_disable'      => $post['is_disable'],
            'update_time'     => time(),
        ], ['id'=> $adminId]);
    }

    /**
     * 管理员新增
     *
     * @param array $post
     * @author zero
     */
    public static function add(array $post): void
    {
        $salt = make_rand_char(6);
        $pwd  = make_md5_str($post['password'].$salt);

        AuthAdmin::create([
            'dept_id'         => $post['dept_id'] ?? 0,
            'post_id'         => $post['post_id'] ?? 0,
            'role_id'         => $post['role_id'],
            'nickname'        => $post['nickname'],
            'username'        => $post['username'],
            'password'        => $pwd,
            'salt'            => $salt,
            'phone'           => $post['phone']  ?? '',
            'email'           => $post['email']  ?? '',
            'is_disable'      => $post['is_disable'],
            'is_delete'       => 0,
            'last_login_ip'   => 0,
            'last_login_time' => 0,
            'create_time'     => time(),
            'update_time'     => time()
        ]);
    }

    /**
     * 管理员删除
     *
     * @param array $post
     * @param int $adminId
     * @throws NotAuthException
     * @throws OperateException
     * @author zero
     */
    public static function edit(array $post, int $adminId): void
    {
        $model = new AuthAdmin();
        $model->checkDataDoesNotExist();

        if ($post['id']==1 && $adminId !== 1) {
            throw new NotAuthException('您没有权限这样做!');
        }

        $salt = make_rand_char(6);
        if (!empty($post['password']) and $post['password']) {
            $post['password'] = make_md5_str($post['password'].$salt);
        } else {
            $admin = $model->field('id,salt,password')
                ->where(['id'=>intval($post['id'])])
                ->findOrEmpty()
                ->toArray();

            $post['password'] = $admin['password'];
            $salt = $admin['salt'];
        }

        AuthAdmin::update([
            'dept_id'     => $post['dept_id'] ?? 0,
            'post_id'     => $post['post_id'] ?? 0,
            'role_id'     => $post['role_id'],
            'nickname'    => $post['nickname'],
            'username'    => $post['username'],
            'password'    => $post['password'],
            'salt'        => $salt,
            'phone'       => $post['phone']  ?? '',
            'email'       => $post['email']  ?? '',
            'is_disable'  => $post['is_disable'],
            'update_time' => time(),
        ], ['id'=>intval($post['id'])]);
    }

    /**
     * 管理员删除
     *
     * @param array $ids
     * @param int $adminId
     * @throws OperateException
     * @author zero
     */
    public static function del(array $ids, int $adminId): void
    {
        if (in_array(1, $ids)) {
            throw new OperateException('系统管理员不允许删除!');
        }

        if (in_array($adminId, $ids)) {
            throw new OperateException('不允许删除自己!');
        }

        AuthAdmin::update([
            'is_delete'   => 1,
            'delete_time' => time(),
        ], array(['id', 'in', $ids]));
    }
}