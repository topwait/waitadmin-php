<?php

namespace app\backend\service\setting;

use app\common\basics\Service;
use app\common\model\NoticeSetting;
use JetBrains\PhpStorm\ArrayShape;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 通知设置服务类
 */
class NoticeService extends Service
{
    /**
     * 场景通知列表
     *
     * @param array $get
     * @return array
     * @throws DbException
     * @author windy
     */
    #[ArrayShape(['count' => "mixed", 'list' => "mixed"])]
    public static function lists(array $get): array
    {
        self::setSearch([
            '=' => ['client'],
        ]);

        $model = new NoticeSetting();
        $lists = $model
            ->withoutField('remarks,variable,is_delete,create_time,delete_time,update_time')
            ->where(['is_delete'=>0])
            ->where(self::$searchWhere)
            ->order('id asc')
            ->paginate([
                'page'      => $get['page']  ?? 1,
                'list_rows' => $get['limit'] ?? 20,
                'var_page'  => 'page'
            ])->toArray();

        foreach ($lists['data'] as &$item) {
            $item['type'] = $item['is_captcha'] ? '验证码' : '通知型';
            $item['sysStatus'] = ($item['sys_template']['status']??0) ? 1 : 0;
            $item['emsStatus'] = ($item['ems_template']['status']??0) ? 1 : 0;
            $item['smsStatus'] = ($item['sms_template']['status']??0) ? 1 : 0;
            unset($item['sys_template']);
            unset($item['ems_template']);
            unset($item['ems_template']);
            unset($item['is_captcha']);
        }

        return ['count'=>$lists['total'], 'list'=>$lists['data']];
    }

    /**
     * 场景通知详情
     *
     * @param int $id
     * @return array
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @author windy
     */
    public static function detail(int $id): array
    {
        $model = new NoticeSetting();
        return $model
            ->withoutField('is_delete,create_time,delete_time,update_time')
            ->where(['id'=>$id])
            ->where(['is_delete'=>0])
            ->findOrFail()
            ->toArray();
    }

    /**
     * 场景通知编辑
     *
     * @param array $post
     * @author windy
     */
    public static function edit(array $post): void
    {
        $sysTemplate = [];
        if (isset($post['sys_status'])) {
            $sysTemplate = [
                'status'  => $post['sys_status'] ?? 0,
                'content' => $post['sys_content'] ?? ''
            ];
        }

        $emsTemplate = [];
        if (isset($post['ems_status'])) {
            $emsTemplate = [
                'status'  => $post['ems_status'] ?? 0,
                'content' => $post['ems_content'] ?? ''
            ];
        }

        $smsTemplate = [];
        if (isset($post['sms_status'])) {
            $smsTemplate = [
                'status'        => $post['sms_status'] ?? 0,
                'content'       => $post['sms_content'] ?? '',
                'template_code' => $post['sms_code'] ?? ''
            ];
        }

        NoticeSetting::update([
            'sys_template' => json_encode($sysTemplate, JSON_UNESCAPED_UNICODE),
            'ems_template' => json_encode($emsTemplate, JSON_UNESCAPED_UNICODE),
            'sms_template' => json_encode($smsTemplate, JSON_UNESCAPED_UNICODE),
            'update_time'  => time()
        ], ['id'=>intval($post['id'])]);
    }
}