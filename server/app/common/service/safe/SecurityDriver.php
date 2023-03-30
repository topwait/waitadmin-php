<?php

namespace app\common\service\safe;

use think\facade\Cache;

/**
 * 安全驱动类
 */
class SecurityDriver extends SecurityBase
{
    /**
     * 会话登录
     *
     * @param int $id
     * @param string $device
     * @return string
     * @author windy
     */
    public static function login(int $id, string $device=''): string
    {
        self::initConfig();

        $fiLogin = false;
        $tokenVa = self::_makeTokenVal($id);
        $session = self::_getSession($id);

        // 首次登录
        if (!$session) {
            $fiLogin = true;
            $session = [
                'id'  => $id,
                'key' => self::_makeBuildKey($id),
                'isDisable'  => false,
                'createTime' => time(),
                'updateTime' => time(),
                'tokenLists' => [[
                    'value'         => $tokenVa,
                    'device'        => $device,
                    'kickOut'       => false,
                    'lastOpTime'    => time(),
                    'createTime'    => time(),
                    'lastIpAddress' => request()->ip(),
                    'lastUaBrowser' => request()->header('User-Agent')
                ]]
            ];
        }

        // 禁止多处登录
        if (!$fiLogin && !self::$config['is-concurrent']) {
            foreach ($session['tokenLists'] as &$item) {
                if ($device && $device !== $item['device']) {
                    continue;
                }
                $item['kickOut'] = true;
            }
        }

        // 禁止多个令牌
        if (!$fiLogin && self::$config['is-shared']) {
            $tokenTimeout = self::$config['token-timeout'];
            $tokenInvalid = self::$config['token-invalid'];

            $tokenLists = $session['tokenLists'][0] ?? [];
            $session['tokenLists'] = [];
            if (count($tokenLists) >= 1
                && $tokenLists['kickOut'] == false
                && ($tokenTimeout==-1 || (time()-$tokenLists['createTime']) < $tokenTimeout)
                && ($tokenInvalid==-1 || (time()-$tokenLists['lastOpTime']) < $tokenInvalid)
            ) {
                $tokenVa = $tokenLists['value'];
            }
        }

        // 设置令牌
        $session['updateTime'] = time();
        $session['tokenLists'][] = [
            'value'         => $tokenVa,
            'device'        => $device,
            'kickOut'       => false,
            'lastOpTime'    => time(),
            'createTime'    => time(),
            'lastIpAddress' => request()->ip(),
            'lastUaBrowser' => request()->header('User-Agent')
        ];

        // 数量限制
        $tokenLimits = self::$config['token-limits'];
        if ($tokenLimits!=-1 && count($session['tokenLists']) > $tokenLimits) {
            $number = count($session['tokenLists']) - $tokenLimits;
            $session['tokenLists'] = array_slice($session['tokenLists'], $number);
        }

        // 记录登录
        self::setSessionVal($id, $session);
        if (self::$config['token-pattern'] !== 'session') {
            $timeout = self::$config['token-timeout'] == -1 ? null : self::$config['token-timeout'];
            Cache::set(self::_makeTokenKey($tokenVa), $id, $timeout);
        }

        return $tokenVa;
    }

    /**
     * 强制指定账号注销下线
     *
     * @param int $id
     * @param string $device (设备SN)
     * @author windy
     */
    public static function logout(int $id, string $device=''): void
    {
        self::initConfig();

        if (!$device) {
            Cache::delete(self::_makeTokenKey($id));
            Cache::delete(self::_makeBuildKey($id));
        } else {
            $session = self::_getSession($id);
            $tokenLists = [];
            foreach ($session['tokenLists']??[] as $item) {
                $createTime = intval($item['createTime']);
                $lastOpTime = intval($item['lastOpTime']);
                if ($item['device'] !== $device && !self::isOvertime($createTime, $lastOpTime)) {
                    $tokenLists[] = $item;
                }
            }
            if (empty($tokenLists)) {
                Cache::delete(self::_makeTokenKey($id));
                Cache::delete(self::_makeBuildKey($id));
            } else {
                self::setSessionVal($id, $session);
            }
        }
    }

    /**
     * 将指定账号踢下线
     *
     * @param int $id
     * @param string $device
     * @author windy
     */
    public static function kickOutById(int $id, string $device='')
    {
        self::initConfig();
        $session = self::_getSession($id);

        if ($device) {
            foreach ($session['tokenLists'] as &$item) {
                if ($item['device'] === $device) {
                    $item['kickOut'] = true;
                }
            }
        } else {
            foreach ($session['tokenLists'] as &$item) {
                $item['kickOut'] = true;
                $createTime = intval($item['createTime']);
                $lastOpTime = intval($item['lastOpTime']);
                if (self::isOvertime($createTime, $lastOpTime)) {
                    unset($item);
                }
            }
        }

        self::setSessionVal($id, $session);
    }

    /**
     * 获取会话信息的列表
     *
     * @param int $id
     * @param string $type 类型: [all=所有, online=在线的,kick=被踢的]
     * @return array
     */
    public static function getSessionList(int $id, string $type='all'): array
    {
        self::initConfig();
        $session = self::_getSession($id);

        if ($type !== 'all') {
            $loginArray = [];
            foreach ($session['tokenLists'] ?? [] as $item) {
                switch ($type) {
                    case 'online':
                        if ($item['kickOut'] == false) {
                            $loginArray[] = $item;
                        }
                        break;
                    case 'kick':
                        if ($item['kickOut'] == true) {
                            $loginArray[] = $item;
                        }
                        break;
                    default:
                        $loginArray[] = $item;
                }

            }
            $session['tokenLists'] = $loginArray;
        }

        return $session;
    }

    /**
     * 设置保存数据
     * Token的过期时间: [-1=永久有效, 单位(秒), 默认30天]
     *
     * @param int $id
     * @param array $session
     */
    private static function setSessionVal(int $id, array $session): void
    {
        $timeout = self::$config['token-timeout'] == -1 ? null : self::$config['token-timeout'];
        Cache::set(self::_makeBuildKey($id), json_encode($session), $timeout);
    }

    /**
     * 验证令牌是否超时
     *
     * @param int $createTime
     * @param int $lastOpTime
     * @return bool (true=已超时, false=未超时)
     * @author windy
     */
    private static function isOvertime(int $createTime, int $lastOpTime): bool
    {
        $tokenTimeout = self::$config['token-timeout'];
        $tokenInvalid = self::$config['token-invalid'];

        // 超过设定时
        if ($tokenTimeout!=-1 && (time()-$createTime) >= $tokenTimeout) {
            return true;
        }

        // 超时未操作
        if ($tokenInvalid!=-1 && (time()-$lastOpTime) >= $tokenInvalid) {
            return true;
        }

        return false;
    }
}