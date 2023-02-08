<?php

namespace app\common\service\security;

use think\facade\Cache;

class SecurityDriver extends BaseService
{
    /**
     * 会话登录
     *
     * @param int $id
     * @param string $device
     * @return string
     * @author windy
     */
    public static function login(int $id, string $device='default'): string
    {
        self::initConfig();

        $fiLogin = false;
        $tokenVa = self::_tokenVal($id);
        $session = self::_session($id);

        // 首次登录
        if (!$session) {
            $fiLogin = true;
            $session = [
                'id'  => $id,
                'key' => self::_buildKey($id),
                'isDisable'  => false,
                'createTime' => time(),
                'updateTime' => time(),
                'tokenLists' => [[
                    'value'         => $tokenVa,
                    'device'        => $device,
                    'kickOut'       => false,
                    'lastOpTime'    => time(),
                    'lastIpAddress' => request()->ip(),
                    'lastUaBrowser' => request()->header('User-Agent')
                ]]
            ];
        }

        // 独立令牌
        if (!$fiLogin && !self::$config['is-shared']) {
            if (!self::$config['is-concurrent']) {
                foreach ($session['tokenLists'] as &$item) {
                    $item['kickOut'] = true;
                }
            }
            $session['updateTime'] = time();
            $session['tokenLists'][] = [
                'value'         => $tokenVa,
                'device'        => $device,
                'kickOut'       => false,
                'lastOpTime'    => time(),
                'lastIpAddress' => request()->ip(),
                'lastUaBrowser' => request()->header('User-Agent')
            ];
        }

        // 共享令牌
        if (!$fiLogin && self::$config['is-shared']) {
            if (!self::$config['is-concurrent']) {
                foreach ($session['tokenLists'] as &$item) {
                    $item['kickOut'] = true;
                }
            }

            $session['updateTime'] = time();
            if (count($session['tokenLists']) == 1 && !$session['tokenLists'][0]['kickOut']) {
                $tokenVa = $session['tokenLists'][0]['value'];
                $session['tokenLists'][0]['lastOpTime']    = time();
                $session['tokenLists'][0]['lastIpAddress'] = request()->ip();
                $session['tokenLists'][0]['lastIpAddress'] = request()->header('User-Agent');
            } else {
                array_unshift($session['tokenLists'], [
                    'value'         => $tokenVa,
                    'device'        => $device,
                    'kickOut'       => false,
                    'lastOpTime'    => time(),
                    'lastIpAddress' => request()->ip(),
                    'lastUaBrowser' => request()->header('User-Agent')
                ]);
            }
        }

        // 记录登录
        $session = json_encode($session);
        $timeout = self::$config['token-timeout'] == -1 ? null : self::$config['token-timeout'];
        Cache::set(self::_buildKey($id), $session, self::$config['token-timeout']);
        if (self::$config['token-pattern'] !== 'session') {
            Cache::set(self::_tokenKey($tokenVa), $id, $timeout);
        }

        return $tokenVa;
    }

    /**
     * 强制指定账号注销下线
     *
     * @param int $id
     * @param string $device
     * @author windy
     */
    public static function logout(int $id, string $device=''): void
    {
        self::initConfig();

        if (!$device) {
            Cache::delete(self::_tokenKey($id));
            Cache::delete(self::_buildKey($id));
        } else {
            $session = self::_session($id);
            $tokenLists = [];
            foreach ($session['tokenLists']??[] as $item) {
                if ($item['device'] !== $device) {
                    $tokenLists[] = $item;
                }
            }
            if (empty($tokenLists)) {
                Cache::delete(self::_tokenKey($id));
                Cache::delete(self::_buildKey($id));
            } else {
                $timeout = self::$config['token-timeout'] == -1 ? null : self::$config['token-timeout'];
                Cache::set(self::_buildKey($id), $session, $timeout);
            }
        }
    }

    /**
     * 检验当前会话是否已经登录
     *
     * @param string $token
     * @return int (0=未登录, 否则返回用户ID)
     */
    public static function checkLogin(string $token): int
    {
        self::initConfig();

        if (self::$config['token-pattern'] == 'session') {
            $id = intval(session($token));
            $token = app('session')->getId();
        } else {
            $id = intval(Cache::get(self::_tokenKey($token), 0));
        }

        $session = self::_session($id);
        foreach ($session['tokenLists']??[] as $item) {
            if ($item['value'] === $token) {
                // token临时有效期(指定时间内无操作就视为token过期)
                if (self::$config['token-invalid'] > -1) {
                    $invalidTime = intval($item['lastOpTime']) + self::$config['token-invalid'];
                    if ($invalidTime <= time()) {
                        return 0;
                    }
                }

                // token有效期,默认30天, -1代表永不过期
                if (self::$config['token-timeout'] > -1) {
                    $expirationTime = intval($item['lastOpTime']) + self::$config['token-timeout'];
                    if ($expirationTime <= time()) {
                        return 0;
                    }
                }

                // 已经登录
                return $id;
            }
        }

        return 0;
    }

    /**
     * 检验当前会话是否已经禁用
     *
     * @return true=已禁用, false=未禁用
     * @author windy
     */
    public static function checkDisable(string $token): bool
    {
        self::initConfig();

        if (self::$config['token-pattern'] == 'session') {
            $id = intval(session($token));
        } else {
            $id = intval(Cache::get(self::_tokenKey($token), 0));
        }

        $session = self::_session($id);
        if (!$session || $session['isDisable']) {
            return true;
        }

        return false;
    }

    /**
     * 强制指定Token注销下线
     *
     * @param string $token
     * @author windy
     */
    public static function logoutByTokenValue(string $token)
    {
        $id = intval(Cache::get(self::_tokenKey($token), 0));
        $session = self::_session($id);

        $tokenLists = [];
        foreach ($session['tokenLists'] as $item) {
            if ($item['value'] !== $token) {
                $tokenLists[] = $item;
            }
        }

        $session['tokenLists'] = $tokenLists;
        $timeout = self::$config['token-timeout'] == -1 ? null : self::$config['token-timeout'];
        Cache::set(self::_buildKey($id), $session, $timeout);
    }

    /**
     * 将指定账号踢下线
     *
     * @param int $id
     * @param string $device
     * @author windy
     */
    public static function kickOut(int $id, string $device='')
    {
        $session = self::_session($id);
        if ($device) {
            foreach ($session['tokenLists'] as &$item) {
                if ($item['device'] === $device) {
                    $item['kickOut'] = true;
                }
            }
        } else {
            foreach ($session['tokenLists'] as &$item) {
                $item['kickOut'] = true;
            }
        }

        $timeout = self::$config['token-timeout'] == -1 ? null : self::$config['token-timeout'];
        Cache::set(self::_buildKey($id), $session, $timeout);
    }

    /**
     * 将指定Token踢下线
     *
     * @param string $token
     */
    public static function kickOutByTokenValue(string $token)
    {
        $id = intval(Cache::get(self::_tokenKey($token), 0));
        $session = self::_session($id);

        foreach ($session['tokenLists']??[] as $item) {
            if ($item['value'] !== $token) {
                $item['kickOut'] = true;
            }
        }

        $timeout = self::$config['token-timeout'] == -1 ? null : self::$config['token-timeout'];
        Cache::set(self::_buildKey($id), $session, $timeout);
    }

    /**
     * 获取会话信息的列表
     *
     * @param int $id
     * @return array
     */
    public static function getSessionInfo(int $id): array
    {
        return self::_session($id);
    }
}