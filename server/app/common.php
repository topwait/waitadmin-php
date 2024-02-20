<?php
// +----------------------------------------------------------------------
// | 公共类库
// +----------------------------------------------------------------------

use app\common\cache\PermsCache;
use app\common\utils\FileUtils;
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
     * @author zero
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
     * @author zero
     */
    function route(string $url, array $data=[]): string
    {
        $entrance = app()->http->getName();
        if (app()->http->getName() === 'backend' || (request()->route()['module']??'') === 'backend') {
            $entrance = config('project.backend_entrance');
        }

        $url = $entrance . '/' . $url;
        if (!empty($data)) {
            $url .= '?'.http_build_query($data);
        }

        if (!str_starts_with($url, '/')) {
            $url = '/' . $url;
        }

        return $url;
    }
}

if (!function_exists('to_ext_icon')) {
    /**
     * 转上传类型图标
     *
     * @param string $url (文件路径)
     * @return string
     * @author zero
     */
    function to_ext_icon(string $url): string
    {
        $ext = FileUtils::getFileExt($url);
        $imageExt = config('project.uploader.image')['ext'];
        $videoExt = config('project.uploader.video')['ext'];

        if (in_array($ext, $imageExt) || in_array($ext, $videoExt)) {
            return $url;
        }

        $packageExt = config('project.uploader.package')['ext'];
        $documentExt = config('project.uploader.document')['ext'];
        if (!in_array($ext, $packageExt) && !in_array($ext, $documentExt)) {
            $ext = 'unknown';
        }

        return '/static/backend/images/attach/'.$ext.'.png';
    }
}

if (!function_exists('make_md5_str')) {
    /**
     * 生成MD5加密串
     *
     * @param string $str (未加密字符)
     * @param string $salt (加密的盐)
     * @return string     (已加密字符)
     * @author zero
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
     * @author zero
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

if (!function_exists('check_perms')) {
    /**
     * 后台按钮权限验证
     *
     * @param string $url (权限标识)
     * @param bool $hide  (隐藏元素)
     * @return string
     * @author zero
     */
    function check_perms(string $url, bool $hide=true): string
    {
        if (str_contains($url, '/')) {
            $auths = $url;
        } else {
            $urlArray = explode('/', ltrim(request()->baseUrl(), '/'));
            array_shift($urlArray);
            array_pop($urlArray);
            $auths = implode('/', $urlArray) . '/' . $url;
        }

        $adminUser = session('adminUser');
        $adminId = $adminUser ? intval($adminUser['id']??0) : 0;
        $perms = PermsCache::get($adminId);

        $super = count($perms) == 1 && $perms[0] == '*';
        if ($perms && (in_array($auths, $perms) || $super)) {
            return '';
        } else {
            if ($hide) {
                return 'layui-hide no-permission';
            }
            return 'layui-btn-forbid layui-btn-disabled';
        }
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
     * @author zero
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
    /**
     * 激活当前URl按钮
     *
     * @param string $url   (对应的URL)
     * @param string $class (激活类样式)
     * @return string
     * @author zero
     */
    function active_url(string $url, string $class = 'active'): string
    {
        if (str_starts_with($url, '/')) {
            if (request()->url() == $url) {
                return $class;
            }
        } else if (request()->action() == $url) {
            return $class;
        }

        return '';
    }
}

if (!function_exists('user_agent')) {
    /**
     * 获取浏览器标识
     *
     * @return string
     * @author zero
     */
    function user_agent(): string
    {
        $userAgent = request()->header('user-agent', '');
        if (str_contains($userAgent, 'Chrome')) {
            return 'chrome';
        } elseif (str_contains($userAgent, 'Firefox')) {
            return 'firefox';
        } elseif (str_contains($userAgent, 'Safari')) {
            return 'safari';
        } elseif (str_contains($userAgent, 'Opera')) {
            return 'opera';
        } elseif (str_contains($userAgent, 'Edge')) {
            return 'edge';
        } elseif (str_contains($userAgent, 'MSIE') || str_contains($userAgent, 'Trident/')) {
            return 'ie';
        } else {
            return 'other';
        }
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
     * @author zero
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
     * @author zero
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