<?php

namespace app\common\service\security;


use think\facade\Cache;

abstract class BaseService
{


    /**
     * 配置参数
     * @var array
     */
    protected array $config = [];

    /**
     * 构造器
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $aConfig = config('security') ?? [];
        $config = array_merge($aConfig, $config);
        $this->config = [
            'token-sign'       => $config['token-sign'] ?? 'api',      // token的标识
            'token-name'       => $config['token-name'] ?? 'token',    // token的名称
            'token-mode'       => $config['token-mode'] ?? 'async',    // token的模式
            'token-timeout'    => $config['token-timeout'] ?? 2592000, // token有效期
            'token-invalid'    => $config['token-invalid'] ?? -1,      // token无效期
            'is-shared'        => $config['token-shared'] ?? false,    // token的共享
            'is-concurrent'    => $config['token-concurrent'] ?? true  // token的并发
        ];
    }

    /**
     * 设置标识
     *
     * @param string $name
     * @return $this
     */
    public function setTokenSign(string $name): static
    {
        $this->config['token-sign'] = $name;
        return $this;
    }

    /**
     * 设置名称
     *
     * @param string $name
     * @return $this
     */
    public function setTokenName(string $name): static
    {
        $this->config['token-name'] = $name;
        return $this;
    }

    /**
     * 设置模式
     *
     * @param string $mode (模式: session/async)
     * @return $this
     */
    public function setTokenModel(string $mode): static
    {
        $this->config['token-mode'] = $mode;
        return $this;
    }

    /**
     * 生成密钥值
     *
     * @param int $id
     * @return string
     */
    protected function _tokenVal(int $id): string
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
    protected function _tokenKey(string $token): string
    {
        if (!empty($this->config['token-name']) && $this->config['token-name']) {
            return $this->config['token-name'] . ':' . 'login:token:' . $token;
        }
        return 'login:token:' . $token;
    }

    /**
     * 获取构建键
     *
     * @param int $id
     * @return string
     */
    protected function _buildKey(int $id): string
    {
        if (!empty($this->config['token-name']) && $this->config['token-name']) {
            return $this->config['token-name'] . ':' . 'login:session:' . $id;
        }
        return 'login:session:' . $id;
    }

    /**
     * 获取结构值
     *
     * @param int $id
     * @return array
     */
    protected function _session(int $id): array
    {
        $cache = Cache::get(self::_buildKey($id));
        if (!$cache) {
            return [];
        }
        return json_decode($cache, true);
    }
}