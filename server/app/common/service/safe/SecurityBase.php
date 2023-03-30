<?php

namespace app\common\service\safe;

use think\facade\Cache;

/**
 * 安全管理基类
 */
abstract class SecurityBase
{
    protected static array $config = [];

    /**
     * 初始化配置
     */
    protected static function initConfig()
    {
        self::$config = [
            // Token的缓存标识: [多应用模式下区分缓存的方式]
            'token-name'     => $config['token-name'] ?? 'wait',
            // Token的授权模式: [async=前后端分离, session=系统会话管理]
            'token-pattern'  => $config['token-pattern'] ?? 'async',
            // Token的数量限制: [-1=不限制数, 单位(个), 默认1万个]
            'token-limits'  => $config['token-limits'] ?? 3,
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
     * 获取令牌键
     *
     * @param string $token
     * @return string
     */
    protected static function _makeTokenKey(string $token): string
    {
        if (!empty(self::$config['token-name']) && self::$config['token-name']) {
            return self::$config['token-name'] . ':' . 'login:token:' . $token;
        }
        return 'login:token:' . $token;
    }

    /**
     * 生成令牌值
     *
     * @param int $id
     * @return string
     */
    protected static function _makeTokenVal(int $id): string
    {
        if (self::$config['token-pattern'] === 'session') {
            return app('session')->getId();
        }

        $length = 8;
        $str = '';
        $strPol = "ABC"."DEF"."GHI"."JKL"."MNO"."PQR"."STU"."VWX"."YZ";
        $max = strlen($strPol) - 1;

        for ($i = 0;
             $i < $length;
             $i++) {
            $str .= $strPol[rand(0, $max)];
        }

        return str_shuffle(md5($id.$str) . $str);
    }

    /**
     * 获取构建键
     *
     * @param int $id
     * @return string
     */
    protected static function _makeBuildKey(int $id): string
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
    protected static function _getSession(int $id): array
    {
        $cache = Cache::get(self::_makeBuildKey($id));
        if (!$cache) {
            return [];
        }
        return json_decode($cache, true);
    }
}