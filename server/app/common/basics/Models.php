<?php
// +----------------------------------------------------------------------
// | WaitAdmin快速开发后台管理系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习程序代码,建议反馈是我们前进的动力
// | 程序完全开源可支持商用,允许去除界面版权信息
// | gitee:   https://gitee.com/wafts/WaitAdmin
// | github:  https://github.com/topwait/waitadmin
// | 官方网站: https://www.waitadmin.cn
// | WaitAdmin团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | Author: WaitAdmin Team <2474369941@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\common\basics;


use app\common\exception\OperateException;
use app\common\utils\UrlUtils;
use think\Model;

/**
 * 模型基类
 *
 * Class Models
 * @package app\common\basics
 */
class Models extends Model
{
    /**
     * 校验数据是否存在
     *
     * @param array|string $where
     * @param string $msg
     * @throws OperateException
     * @author zero
     */
    public function checkDataDoesNotExist(array|string $where = [], string $msg = '数据不存在!'): void
    {
        $param = request()->param();
        if (is_string($where)) {
            $msg = $where;
        } elseif (is_array($where) && empty($where)) {
            $where[] = ['is_delete', '=', 0];
            $where[] = ['id', '=', intval($param['id'])];
        }

        $result = self::field('id')->where($where)->findOrEmpty();
        if ($result->isEmpty()) {
            throw new OperateException($msg);
        }
    }

    /**
     * 验证数据是否已存在
     *
     * @param array|string $where
     * @param string $msg
     * @throws OperateException
     * @author zero
     */
    public function checkDataAlreadyExist(array|string $where = [], string $msg = '数据已存在!'): void
    {
        $param = request()->param();
        if (is_string($where)) {
            $msg = $where;
        } elseif (is_array($where) && empty($where)) {
            $where[] = ['id', '=', intval($param['id'])];
            $where[] = ['is_delete', '=', 0];
        }

        $result = self::field('id')->where($where)->findOrEmpty();
        if (!$result->isEmpty()) {
            throw new OperateException($msg);
        }
    }

    /**
     * 获取器: 处理图片路径
     *
     * @param $value
     * @return string
     * @author zero
     */
    public function getImageAttr($value): string
    {
        if (!$value) {
            return '';
        } else {
            return UrlUtils::toAbsoluteUrl($value);
        }
    }

    /**
     * 获取器: 处理轮播图片
     *
     * @param $value
     * @return array
     * @author zero
     */
    public function getBannerAttr($value): array
    {
        if (!$value) {
            return [];
        } else {
            $data = explode(',', $value);
            foreach ($data as &$url) {
                $url = UrlUtils::toAbsoluteUrl($url);
            }
            return $data;
        }
    }

    /**
     * 修改器: 处理图片路径
     *
     * @param $value
     * @return string
     * @author zero
     */
    public function setImageAttr($value): string
    {
        return UrlUtils::toRelativeUrl($value);
    }

    /**
     * 修改器: 处理轮播图
     *
     * @param $value
     * @return string
     * @author zero
     */
    public function setBannerAttr($value): string
    {
        if (!$value) {
            return '';
        } else {
            $data = [];
            foreach ($value as $uri) {
                $data[] = UrlUtils::toRelativeUrl($uri);
            }
            return implode(',', $data);
        }
    }

    /**
     * 处理IDEA的警告
     *
     * @param $join
     * @param $condition
     * @return $this
     */
    public function join($join, $condition): Models
    {
        unset($join);
        unset($condition);
        return $this;
    }

    /**
     * 处理IDEA的警告
     *
     * @param $join
     * @param $condition
     * @return $this
     */
    public function leftJoin($join, $condition): Models
    {
        unset($join);
        unset($condition);
        return $this;
    }

    /**
     * 处理IDEA的警告
     *
     * @param $join
     * @param $condition
     * @return $this
     */
    public function rightJoin($join, $condition): Models
    {
        unset($join);
        unset($condition);
        return $this;
    }

    /**
     * 处理IDEA的警告
     *
     * @param $field
     * @param $value
     * @return $this
     */
    public function exp($field, $value): Models
    {
        unset($field);
        unset($value);
        return $this;
    }
}