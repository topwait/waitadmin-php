<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/waitadmin-php
// | github:  https://github.com/topwait/waitadmin-php
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\backend\service\system;

use app\common\basics\Service;
use app\common\utils\FileUtils;

/**
 * 系统缓存清除服务类
 */
class ClearService extends Service
{
    /**
     * 清空缓存
     *
     * @param array $post
     * @author zero
     */
    public static function clean(array $post): void
    {
        $dirs = [];
        foreach (scandir(root_path().'runtime') as $dir) {
            if (!in_array($dir, ['.', '..', '.gitignore', 'log', 'session', 'cache'])) {
                $path = str_replace('\\', '/', root_path().'runtime/'.$dir);
                $dirs[] = $path;
            }
        }

        foreach ($post['type'] as $type) {
            switch (intval($type)) {
                case 1: //系统缓存
                    FileUtils::rmdir(root_path().'runtime/cache/');
                    foreach ($dirs as $dir) {
                        FileUtils::rmdir($dir.'/cache/');
                    }
                    break;
                case 2: //登录缓存
                    FileUtils::rmdir(root_path().'runtime/session/');
                    foreach ($dirs as $dir) {
                        FileUtils::rmdir($dir.'/session/');
                    }
                    break;
                case 3: //模板缓存
                    FileUtils::rmdir(public_path().'runtime/temp/');
                    foreach ($dirs as $dir) {
                        FileUtils::rmdir($dir.'/temp/');
                    }
                    break;
                case 4: //日志文件
                    FileUtils::rmdir(root_path().'runtime/log/');
                    foreach ($dirs as $dir) {
                        FileUtils::rmdir($dir.'/log/');
                    }
                    break;
                case 5: //临时图片
                    FileUtils::rmdir(root_path().'public/storage/temp/');
                    break;
            }
        }
    }
}