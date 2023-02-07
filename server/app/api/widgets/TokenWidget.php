<?php

namespace app\api\widgets;

use think\facade\Cache;

class TokenWidget
{
    private static string $tokenKey = 'login:token:';
    private static string $buildKey = 'login:session:';

    private static array $config = [
        'token-name'       => '',       // token的名称
        'token-timeout'    => 2592000,  // token有效期
        'activity-timeout' => -1,       // token无效期
        'is-share'         => false,    // token同用吗
        'is-concurrent'    => true     // 挤掉旧登录吗
    ];

    /**
     * 会话登录
     */
    public static function login(int $id)
    {
        $tokenVa = self::_makeTokenVal($id);
        $session = self::_loginSession($id);

        if (!$session) {
            // 新建令牌
            $build = json_encode([
                'id' => self::_loginBuildKey($id),
                'createTime' => time(),
                'updateTime' => time(),
                'tokenLists' => [[
                    'value'         => $tokenVa,
                    'device'        => 1,
                    'lastOpTime'    => time(),
                    'lastIpAddress' => request()->ip(),
                    'lastUaBrowser' => request()->header('User-Agent')
                ]]
            ]);
        } else if (!self::$config['is-share']) {
            // 独立令牌
            $session['updateTime'] = time();
            $session['tokenLists'] = self::$config['is-concurrent'] ? [] : $session['tokenLists'];
            $session['tokenLists'][] = [
                'value'         => $tokenVa,
                'device'        => 1,
                'lastOpTime'    => time(),
                'lastIpAddress' => request()->ip(),
                'lastUaBrowser' => request()->header('User-Agent')
            ];
            $build = $session;
        } else {
            // 共享令牌
            $session['updateTime'] = time();
            if (self::$config['is-concurrent']) {
                $session['tokenLists'] = [];
                $session['tokenLists'][] = [
                    'value'         => $tokenVa,
                    'device'        => 1,
                    'lastOpTime'    => time(),
                    'lastIpAddress' => request()->ip(),
                    'lastUaBrowser' => request()->header('User-Agent')
                ];
            } else {
                $tokenVa   = $session['tokenLists'][0]['value'];
                $loginItem = $session['tokenLists'][0];
                $session['tokenLists'] = [];
                $loginItem['lastOpTime']    = time();
                $loginItem['lastIpAddress'] = request()->ip();
                $loginItem['lastUaBrowser'] = request()->header('User-Agent');
                $session['tokenLists'][] = $loginItem;
            }
            $build = $session;
        }

        if (self::$config['token-timeout'] !== -1) {
            Cache::set(self::_loginTokenKey($tokenVa), $id, self::$config['token-timeout']);
            Cache::set(self::_loginBuildKey($id), $build, self::$config['token-timeout']);
        } else {
            Cache::set(self::_loginTokenKey($tokenVa), $id);
            Cache::set(self::_loginBuildKey($id), $build);
        }
    }

    /**
     * 注销登录
     */
    public static function logout()
    {

    }

    /**
     * 踢人下线
     */
    public static function kickOut()
    {


    }

    public static function checkLogin()
    {
        $cache = Cache::get('login:session:1');
        $cacheArray = json_decode($cache, true);

        $cacheArray['updateTime'] = time();
        foreach ($cacheArray['tokenLists'] as &$item) {
            if ($item['value'] == '' && $item['device'] == 1) {
                $item['lastOpTime'] = time();
                $item['lastIpAddress'] = request()->ip();
                $item['lastUaBrowser'] = request()->header('User-Agent');
            }
        }

        Cache::set('login:session:1', json_encode($cacheArray), 7200);

    }

    public static function checkDisable()
    {

    }

    /**
     * 生成密钥值
     *
     * @param int $id
     * @return string
     */
    private static function _makeTokenVal(int $id): string
    {
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
    private static function _loginTokenKey(string $token): string
    {
        if (!empty(self::$config['token-name']) && self::$config['token-name']) {
            return self::$config['token-name'] . ':' . self::$tokenKey . $token;
        }
        return self::$tokenKey . $token;
    }

    /**
     * 获取构建键
     *
     * @param int $id
     * @return string
     */
    private static function _loginBuildKey(int $id): string
    {
        if (!empty(self::$config['token-name']) && self::$config['token-name']) {
            return self::$config['token-name'] . ':' . self::$buildKey . $id;
        }
        return self::$buildKey . $id;
    }

    /**
     * 获取结构值
     *
     * @param int $id
     * @return array
     */
    private static function _loginSession(int $id): array
    {
        $cache = Cache::get(self::_loginBuildKey($id));
        if (!$cache) {
            return [];
        }
        return json_decode($cache, true);
    }
}