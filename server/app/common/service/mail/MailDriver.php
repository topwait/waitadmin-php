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

namespace app\common\service\mail;


use app\common\utils\ConfigUtils;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * 邮件驱动类
 *
 * Class MailDriver
 * @package app\common\service\mail
 */
class MailDriver
{
    /**
     * 邮件配置
     * @var array
     */
    private mixed $config;

    /**
     * 邮件对象
     * @var PHPMailer
     */
    private PHPMailer $mail;

    /**
     * 发件人
     * @var array
     */
    private array $form;

    /**
     * 初始化
     *
     * MailDriver constructor.
     */
    public function __construct()
    {
        $this->mail = new PHPMailer();
        $this->config = ConfigUtils::get('mail');
    }

    /**
     * 发送邮件
     *
     * @throws @\PHPMailer\PHPMailer\Exception
     * @author windy
     */
    public function send(): void
    {
        $this->mail->CharSet   = 'UTF-8';            // 设定邮件编码
        $this->mail->SMTPDebug = 0;                  // 启用详细调试输出
        $this->mail->isSMTP();                       // SMTP启用
        $this->mail->SMTPAuth   = true;              // SMTP认证
        $this->mail->Host       = $this->config['mail_smtp_host']   ?? '';  // SMTP服务器
        $this->mail->Username   = $this->config['mail_smtp_user']   ?? '';  // SMTP用户名
        $this->mail->Password   = $this->config['mail_smtp_pass']   ?? '';  // SMTP的密码
        $this->mail->SMTPSecure = $this->config['mail_verify_type'] ?? '';  // 允许协议[TLS/SSL]
        $this->mail->Port       = $this->config['mail_smtp_port']   ?? '';  // 服务器端口[25/465]
        $this->mail->Debugoutput = 'html';
        $this->mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ];

        // 发件人
        if ($this->form) {
            $this->mail->setFrom($this->form['address'], $this->form['name']);
        } else {
            $this->mail->setFrom($this->config['mail_from_user']);
        }

        // 发送
        $status = $this->mail->send();
        if (!$status) {
            throw new Exception($this->mail->ErrorInfo);
        }
    }

    /**
     * 设置发件人
     *
     * @author windy
     * @param string $address
     * @param string $name
     * @return MailDriver
     * @throws @\PHPMailer\PHPMailer\Exception
     */
    public function setFrom(string $address, string $name = ''): MailDriver
    {
        $this->form = ['address'=>$address, 'name'=>$name];
        return $this;
    }

    /**
     * 设置收件人
     *
     * @param string $address
     * @param string $name
     * @return MailDriver
     * @throws @\PHPMailer\PHPMailer\Exception
     * @author windy
     */
    public function addAddress(string $address, string $name = ''): MailDriver
    {
        $this->mail->addAddress($address, $name);
        return $this;
    }

    /**
     * 设置回复邮箱
     * 建议和发件人一致
     *
     * @author windy
     * @param string $address
     * @param string $name
     * @return MailDriver
     * @throws @\PHPMailer\PHPMailer\Exception
     */
    public function addReplyTo(string $address, string $name = ''): MailDriver
    {
        $this->mail->addReplyTo($address, $name);
        return $this;
    }

    /**
     * 设置抄送
     *
     * @author widny
     * @param string $address
     * @param string $name
     * @return MailDriver
     * @throws @\PHPMailer\PHPMailer\Exception
     */
    public function addCC(string $address, string $name = ''): MailDriver
    {
        $this->mail->addCC($address, $name);
        return $this;
    }

    /**
     * 设置密送
     *
     * @author windy
     * @param string $address
     * @param string $name
     * @return MailDriver
     * @throws @\PHPMailer\PHPMailer\Exception
     */
    public function addBCC(string $address, string $name = ''): MailDriver
    {
        $this->mail->addBCC($address, $name);
        return $this;
    }

    /**
     * 设置附件
     *
     * @author windy
     * @param string $path
     * @param string $name
     * @param string $encoding
     * @param string $type
     * @param string $disposition
     * @return MailDriver
     * @throws @\PHPMailer\PHPMailer\Exception
     */
    public function addAttachment(string $path, string $name = '', string $encoding = 'base64', string $type = '', string $disposition = 'attachment'): MailDriver
    {
        $this->mail->addAttachment($path, $name, $encoding, $type, $disposition);
        return $this;
    }

    /**
     * 设置HTML格式
     *
     * @author windy
     * @param bool $isHtml
     * @return MailDriver
     */
    public function isHTML(bool $isHtml = true): MailDriver
    {
        $this->mail->isHTML($isHtml);
        return $this;
    }

    /**
     * 设置邮件标题
     *
     * @author windy
     * @param string $title
     * @return MailDriver
     */
    public function subject(string $title): MailDriver
    {
        $this->mail->Subject = $title;
        return $this;
    }

    /**
     * 设置邮件内容
     *
     * @author windy
     * @param string $body (内容)
     * @return MailDriver
     */
    public function body(string $body): MailDriver
    {
        $this->mail->Body = $body;
        return $this;
    }

    /**
     * 设置邮件客户端不支持HTML时显示的内容
     *
     * @author windy
     * @param string $body (内容)
     * @return MailDriver
     */
    public function altBody(string $body): MailDriver
    {
        $this->mail->AltBody = $body;
        return $this;
    }
}