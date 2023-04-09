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

namespace app\common\utils;


use app\common\model\sys\SysConfig;
use think\facade\Cache;

/**
 * 配置工具
 *
 * Class ConfigUtils
 * @package app\common\utils
 */
class ConfigUtils
{
    /**
     * 系统配置缓存键
     */
    private const SYSTEM_CONFIG_KEY = 'sys:config';

    /**
     * 配置读取
     *
     * @param string $type (类型)
     * @param string $key (键名)
     * @param null $default (默认)
     * @return mixed
     * @throws @\think\db\exception\DataNotFoundException
     * @throws @\think\db\exception\DbException
     * @throws @\think\db\exception\ModelNotFoundException
     * @author zero
     */
    public static function get(string $type, string $key='', $default=null): mixed
    {
        $cacheData = Cache::get(self::SYSTEM_CONFIG_KEY);
        if (empty($cacheData)) {
            $model = new SysConfig();
            $lists = $model->withoutField('create_time,update_time')->select()->toArray();

            $results = [];
            foreach ($lists as $value) {
                $results[$value['type']][$value['key']] = $value['value'];
            }

            $cacheData = $results;
            Cache::set(self::SYSTEM_CONFIG_KEY, json_encode($results));
        } else {
            $cacheData = json_decode($cacheData, true);
        }

        if ($key) {
            $value = $cacheData[$type][$key] ?? null;

            if ($value !== null && $value !== '') {
                $json = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $json;
                }
            }

            if ($value === null && $default !== null) {
                return $default;
            }

            return $value;
        }

        $data = $cacheData[$type] ?? null;
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if ($v == null) {
                    $data[$k] = $v;
                    continue;
                }
                $json = json_decode($v, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data[$k] = $json;
                }
            }
        }

        if ($data === null && $default !== null) {
            return $default;
        }

        return $data;
    }

    /**
     * 配置设置
     *
     * @param string $type (类型)
     * @param string $key (键名)
     * @param mixed $value (键值)
     * @param string $remarks (备注)
     * @author zero
     */
    public static function set(string $type, string $key, mixed $value, string $remarks=''): void
    {
        Cache::delete(self::SYSTEM_CONFIG_KEY);
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $model = new SysConfig();
        if ($model->where(['type'=>$type, 'key'=>$key])->findOrEmpty()->isEmpty()) {
            SysConfig::create([
                'type'        => $type,
                'key'         => $key,
                'value'       => $value,
                'remarks'     => $remarks,
                'create_time' => time(),
                'update_time' => time()
            ]);
        } else {
            $data = [
                'type'        => $type,
                'key'         => $key,
                'value'       => $value,
                'update_time' => time()
            ];
            if ($remarks) {
                $data['remarks'] = $remarks;
            }
            SysConfig::update($data, ['type'=>$type, 'key'=>$key]);
        }
    }

    /**
     * 配置(数组)
     *
     * @param string $type   (类型)
     * @param array $results (集合)
     */
    public static function setItem(string $type, array $results)
    {
        foreach ($results as $key => $value) {
            self::set($type, $key, $value);
        }
    }
}