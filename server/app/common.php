<?php
// +----------------------------------------------------------------------
// | 公共类库
// +----------------------------------------------------------------------

use JetBrains\PhpStorm\Pure;
use think\facade\Lang;

if (!function_exists('__')) {
    /**
     * 获取语言变量值
     *
     * @param string $name (语言变量名)
     * @param array $vars (动态变量值)
     * @param string $lang (语言)
     * @return mixed
     * @author windy
     */
    function __(string $name, array $vars = [], string $lang = ''): mixed
    {
        if (is_numeric($name) || !$name) {
            return $name;
        }
        if (!is_array($vars)) {
            $vars = func_get_args();
            array_shift($vars);
            $lang = '';
        }
        return Lang::get($name, $vars, $lang);
    }
}

if (!function_exists('route')) {
    /**
     * 生成路由地址
     *
     * @param string $url
     * @param array $data
     * @return string
     * @author windy
     */
    function route(string $url, array $data=[]): string
    {
        $entrance = app()->http->getName();
        if (app()->http->getName() === 'backend' || (request()->route()['module']??'') === 'backend') {
            $entrance = config('app.backend_entrance');
        }

        $url = $entrance . '/' . $url;
        if (!empty($data)) {
            $url .= '?'.http_build_query($data);
        }

        return $url;
    }
}

if (!function_exists('make_md5_str')) {
    /**
     * 生成MD5加密串
     *
     * @param string $str (未加密字符)
     * @param string $salt (加密的盐)
     * @return string     (已加密字符)
     * @author windy
     */
    #[Pure]
    function make_md5_str(string $str, string $salt=''): string
    {
        $baseStr = md5('CORRECT');
        return md5($baseStr . $str . $salt);
    }
}

if (!function_exists('make_rand_char')) {
    /**
     * 生成随机字符串
     *
     * @param int $length (生成长度)
     * @return string (随机字符)
     * @author windy
     */
    function make_rand_char(int $length): string
    {
        $str = '';
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0;
             $i < $length;
             $i++) {
            $str .= $strPol[rand(0, $max)];
        }

        return $str;
    }
}

if (!function_exists('make_rand_code')) {
    /**
     * 生成随机编码值
     *
     * @param $model (模型实体)
     * @param string $field (字符串名称)
     * @param int $length (生成长度)
     * @param string $prefix (生成前缀)
     * @return string
     */
    function make_rand_code($model, string $field = 'sn', int $length = 8, string $prefix = ''): string
    {
        $rand_str = '';
        for ($i = 0; $i < $length; $i++) {
            $rand_str .= mt_rand(0, 9);
        }

        if ($model == null) {
            return $prefix . $rand_str;
        }

        $code = $prefix . $rand_str;
        if ($model->where([$field => $code])->findOrEmpty()->toArray()) {
            return make_rand_code($model, $field, $length);
        }
        return $code;
    }
}

if (!function_exists('format_bytes')) {
    /**
     * 将字节转换为可读文本
     *
     * @param int $size (大小)
     * @param string $delimiter (分隔符)
     * @param int $precision (小数位数)
     * @return string
     * @author windy
     */
    #[Pure]
    function format_bytes(int $size, string $delimiter = '', int $precision = 2): string
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 6; $i++) {
            $size /= 1024;
        }
        return round($size, $precision) . $delimiter . $units[$i];
    }
}

if (!function_exists('active_url')) {
    function active_url(string $url, $class = 'active'): string
    {
        if (str_starts_with('/', $url)) {
            if (request()->url() == $url) {
                return $class;
            }
        } else {
            if (request()->action() == $url) {
                return $class;
            }
        }

        return '';
    }
}

if (!function_exists('curl_get')) {
    /**
     * 发起GET请求
     *
     * @param string $url (请求链接)
     * @param array $params (请求参数)
     * @param array $options (其它参数)
     * @return mixed
     * @author windy
     */
    function curl_get(string $url, array $params = [], array $options=[]): mixed
    {
        if (isset($params)) {
            $url = $url . '?' . http_build_query($params);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5000);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $headers[] = 'Content-Type: application/json; charset=utf-8';
        $headers = array_merge($headers, $options['headers']??[]);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = curl_exec($curl);
        if (curl_error($curl)) {
            return false;
        } else {
            curl_close($curl);
            try {
                return json_decode($data, true);
            } catch (Exception) {
                return $data;
            }
        }
    }
}

if (!function_exists('curl_post')) {
    /**
     * 发起POST请求
     *
     * @param string $url (请求链接)
     * @param array $data (请求数据)
     * @param array $options (其它参数)
     * @return mixed
     * @author windy
     */
    function curl_post(string $url, array $data = [], array $options=[]): mixed
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $headers[] = 'Content-Type: application/json; charset=utf-8';
        $headers = array_merge($headers, $options['headers']??[]);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = curl_exec($curl);
        if (curl_error($curl)) {
            print "Error: " . curl_error($curl);
            return false;
        } else {
            curl_close($curl);
            try {
                return json_decode($data, true);
            } catch (Exception) {
                return $data;
            }
        }
    }
}