<?php

namespace app\common\service\security;

use think\facade\Cache;

class SecurityDrive extends BaseService
{
    /**
     * 会话登录
     *
     * @param int $id
     * @param string $device
     * @author windy
     */
    public function login(int $id, string $device='default')
    {
        $fiLogin = false;
        $tokenVa = $this->_tokenVal($id);
        $session = $this->_session($id);

        // 首次登录
        if (!$session) {
            $fiLogin = true;
            $session = [
                'id'  => $id,
                'key' => $this->_buildKey($id),
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
        if (!$fiLogin && !$this->config['is-shared']) {
            if (!$this->config['is-concurrent']) {
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
        if (!$fiLogin && $this->config['is-shared']) {
            if (!$this->config['is-concurrent']) {
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
        $timeout = $this->config['token-timeout'] == -1 ? null : $this->config['token-timeout'];
        Cache::set($this->_buildKey($id), $session, $this->config['token-timeout']);
        if ($this->config['token-mode'] !== 'session') {
            Cache::set($this->_tokenKey($tokenVa), $id, $timeout);
        }
    }

    /**
     * 强制指定账号注销下线
     *
     * @param int $id
     * @param string $device
     * @author windy
     */
    public function logout(int $id, string $device='')
    {
        if (!$device) {
            Cache::delete($this->_tokenKey($id));
            Cache::delete($this->_buildKey($id));
        } else {
            $session = $this->_session($id);
            $tokenLists = [];
            foreach ($session['tokenLists'] as $item) {
                if ($item['device'] !== $device) {
                    $tokenLists[] = $item;
                }
            }
            if (empty($tokenLists)) {
                Cache::delete($this->_tokenKey($id));
                Cache::delete($this->_buildKey($id));
            } else {
                $timeout = $this->config['token-timeout'] == -1 ? null : $this->config['token-timeout'];
                Cache::set($this->_buildKey($id), $session, $timeout);
            }
        }
    }

    /**
     * 强制指定Token注销下线
     *
     * @param string $token
     * @author windy
     */
    public function logoutByTokenValue(string $token)
    {
        $id = intval(Cache::get($this->_tokenKey($token), 0));
        $session = $this->_session($id);

        $tokenLists = [];
        foreach ($session['tokenLists'] as $item) {
            if ($item['value'] !== $token) {
                $tokenLists[] = $item;
            }
        }

        $session['tokenLists'] = $tokenLists;
        $timeout = $this->config['token-timeout'] == -1 ? null : $this->config['token-timeout'];
        Cache::set($this->_buildKey($id), $session, $timeout);
    }

    /**
     * 将指定账号踢下线
     *
     * @param int $id
     * @param string $device
     * @author windy
     */
    public function kickOut(int $id, string $device='')
    {
        $session = $this->_session($id);
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

        $timeout = $this->config['token-timeout'] == -1 ? null : $this->config['token-timeout'];
        Cache::set($this->_buildKey($id), $session, $timeout);
    }

    /**
     * 将指定Token踢下线
     *
     * @param string $token
     */
    public function kickOutByTokenValue(string $token)
    {
        $id = intval(Cache::get($this->_tokenKey($token), 0));
        $session = $this->_session($id);

        foreach ($session['tokenLists'] as $item) {
            if ($item['value'] !== $token) {
                $item['kickOut'] = true;
            }
        }

        $timeout = $this->config['token-timeout'] == -1 ? null : $this->config['token-timeout'];
        Cache::set($this->_buildKey($id), $session, $timeout);
    }

    /**
     * 检验当前会话是否已经登录
     *
     * @return true=已登录, false=未登录
     * @author windy
     */
    public function checkLogin(): bool
    {
        if ($this->config['token-mode'] == 'session') {
            $id = session($this->config['token-name']);
        } else {
            $token = request()->header($this->config['token-name']);
            $id = intval(Cache::get($this->_tokenKey($token), 0));
        }

        $session = $this->_session($id);
        if (!$session || !$id) {
            return false;
        }

        foreach ($session['tokenLists'] as $item) {
            if ($item['value'] !== $token) {
                $item['kickOut'] = true;
            }
        }

        return false;
    }

    /**
     * 检验当前会话是否已经禁用
     *
     * @return true=已禁用, false=未禁用
     * @author windy
     */
    public function checkDisable(): bool
    {
        return false;
    }

    /**
     * 获取当前会话账号ID
     *
     * @return int 0=失败,大于0=成功
     * @author windy
     */
    public function getLoginId(): int
    {
        return 0;
    }

    /**
     * 获取指定Token的账号ID
     *
     * @param string $token
     */
    public function getLoginIdByToken(string $token)
    {

    }

    /**
     * 获取当前会话剩余有效期(s,-1表示永久)
     */
    public function getTokenTimeout()
    {

    }

    /**
     * 获取当前会话Token名称
     *
     * @return string
     * @author windy
     */
    public function getTokenName(): string
    {
        return '';
    }

    /**
     * 获取当前会话的Token信息参数
     */
    public function getTokenInfo()
    {

    }

}