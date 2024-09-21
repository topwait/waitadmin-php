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

namespace app\common\service\msg\engine;

use app\common\enums\NoticeEnum;
use app\common\model\notice\NoticeRecord;
use app\common\service\sms\SmsDriver;
use app\common\utils\ConfigUtils;

/**
 * 短信消息服务
 */
class SmsMsgService
{
    /**
     * 发送短信通知
     *
     * @param array $params   (参数)
     * @param array $template (模板)
     * @author zero
     */
    public function send(int $scene, array $params, array $template): void
    {
        // 创建发送记录
        $noticeRecord = NoticeRecord::create([
            'scene'       => $scene,
            'user_id'     => $params['user_id']??0,
            'account'     => $params['mobile'],
            'title'       => $template['name'],
            'code'        => $this->getCode($params, $template),
            'content'     => $this->getContent($params, $template),
            'receiver'    => $template['get_client'],
            'sender'      => NoticeEnum::SENDER_SMS,
            'status'      => NoticeEnum::STATUS_WAIT,
            'is_read'     => NoticeEnum::VIEW_UNREAD,
            'is_captcha'  => $template['is_captcha'],
            'expire_time' => $params['expire_time']??time()+900,
            'create_time' => time(),
            'update_time' => time(),
        ]);

        // 发送短信通知
        $result = (new SmsDriver())->sendSms([
            'mobile'  => $params['mobile'],
            'tplCode' => $template['sms_template']['template_code'],
            'params'  => $this->getSmsParams($params, $template)
        ]);

        // 是否发送成功
        if ($result === true) {
            NoticeRecord::update([
                'status'      => NoticeEnum::STATUS_OK,
                'update_time' => time()
            ], ['id'=>$noticeRecord['id']]);
        } else {
            NoticeRecord::update([
                'status'      => NoticeEnum::STATUS_FAIL,
                'error'       => $result,
                'update_time' => time()
            ], ['id'=>$noticeRecord['id']]);
        }
    }

    /**
     * 获取短信内容(替换模板变量)
     *
     * @param array $params
     * @param array $template
     * @return string
     * @author zero
     */
    private function getContent(array $params, array $template): string
    {
        $content = $template['sms_template']['content']??'';
        foreach ($params as $item => $val) {
            $search_replace = '{' . $item . '}';
            $content = str_replace($search_replace, $val, $content);
        }
        return (string)$content;
    }

    /**
     * 获取短信验证码(某些场景不需要)
     *
     * @param $params
     * @param $template
     * @return string
     * @author zero
     */
    private function getCode($params, $template): string
    {
        $code = '';
        if ($template['is_captcha']) {
            $code = array_intersect_key($params, $template['variable']);
            if ($code) {
                return array_shift($code);
            }
        }
        return (string)$code;
    }

    /**
     * 获取短信参数(对腾讯短信作特殊处理)
     *
     * @param $params
     * @param $template
     * @return array
     * @author zero
     */
    private function getSmsParams($params, $template): array
    {
        // 获取当前短信引擎
        $engine = ConfigUtils::get('sms', 'default', 'aliyun');
        if ($engine != 'tencent') {
            return $params;
        }

        // 获取变量名数组
        $arr = [];
        $content = $template['sms_template']['content'];
        foreach ($params as $item => $val) {
            $search = '{' . $item . '}';
            if(str_contains($content, $search) && !in_array($item, $arr)) {
                $arr[] = $item;
            }
        }

        // 调整好顺序的变量名数组
        $arr2 = [];
        if (!empty($arr)) {
            foreach ($arr as $v) {
                $key = strpos($content, $v);
                $arr2[$key] = $v;
            }
        }

        // 以小到大的排序的数组
        ksort($arr2);
        $arr3 = array_values($arr2);

        // 获取变量数组的对应值
        $arr4 = [];
        foreach ($arr3 as $v2) {
            if(isset($params[$v2])) {
                $arr4[] = $params[$v2];
            }
        }

        // 返回已处理的变量结果
        return $arr4;
    }
}