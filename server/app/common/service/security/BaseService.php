<?php

namespace app\common\service\security;

use Exception;
use think\facade\Cache;

abstract class BaseService
{
    protected static array $config = [];

    /**
     * 初始化配置
     */
    protected static function initConfig()
    {
        self::$config = [
            // Token的缓存标识: [多应用模式下区分缓存的方式]
            'token-name'     => $config['token-name'] ?? 'users',
            // Token的授权模式: [async=前后端分离, session=系统会话管理]
            'token-pattern'  => $config['token-pattern'] ?? 'async',
            // Token的过期时间: [-1=永久有效, 单位(秒), 默认30天]
            'token-timeout'  => $config['token-timeout'] ?? 2592000,
            // Token的时效期限: [指定时间内无操作就视为token过期, 单位(秒)]
            'token-invalid'  => $config['token-invalid'] ?? -1,
            // 是否允许同一账号并发登录: [true=允许一起登录, false=新登录挤掉旧登录]
            'is-concurrent'  => $config['token-concurrent'] ?? true,
            // 多人登录同一账号共用令牌: [true=所有登录共用一个token, false=每次登录新建一个token]
            'is-shared'      => $config['token-shared'] ?? false
        ];
    }

    /**
     * 生成密钥值
     *
     * @param int $id
     * @return string
     */
    protected static function _tokenVal(int $id): string
    {
        if (self::$config['token-pattern'] === 'session') {
            return app('session')->getId();
        }

        $length = 8;
        $str = '';
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $max = strlen($strPol) - 1;

        for ($i = 0;
             $i < $length;
             $i++) {
            $str .= $strPol[rand(0, $max)];
        }

        return str_shuffle(md5($id.$str) . $str);
    }

    /**
     * 获取令牌键
     *
     * @param string $token
     * @return string
     */
    protected static function _tokenKey(string $token): string
    {
        if (!empty(self::$config['token-name']) && self::$config['token-name']) {
            return self::$config['token-name'] . ':' . 'login:token:' . $token;
        }
        return 'login:token:' . $token;
    }

    /**
     * 获取构建键
     *
     * @param int $id
     * @return string
     */
    protected static function _buildKey(int $id): string
    {
        if (!empty(self::$config['token-name']) && self::$config['token-name']) {
            return self::$config['token-name'] . ':' . 'login:session:' . $id;
        }
        return 'login:session:' . $id;
    }

    /**
     * 获取结构值
     *
     * @param int $id
     * @return array
     */
    protected static function _session(int $id): array
    {
        $cache = Cache::get(self::_buildKey($id));
        if (!$cache) {
            return [];
        }
        return json_decode($cache, true);
    }
}