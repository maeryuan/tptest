<?php

namespace app\market\controller;

use think\Controller;
use PHPMailer\PHPMailer;

class Email extends Controller {

    public function index() {
        $sendAddres = '18401514128@163.com';
//        $receiveAddres='1047457825@QQ.com';
        $receiveAddres = '313557552@QQ.com';
        $senderInfo = '网易send test email';
        $emailTitle = "这是一个测试邮件";
        $receiverInfo = '腾讯';
        $emailContent = "邮件内容是 <b>我就是玩玩</b>，哈哈哈！";
        $this->email($sendAddres, $senderInfo, $receiveAddres, $receiverInfo, $emailTitle, $emailContent);
    }

    /**
     * 发送邮箱验证码
     * @param type $sendAddres  发件人地址
     * @param type $senderInfo  发件人信息（名字或公司名等）
     * @param type $receiveAddres  收件人地址
     * @param type $receiverInfo   收件人信息（收件人名字等）
     * @param type $emailTitle      Email标题
     * @param type $emailContent    Email内容（body你要发送的具体内容）
     */
    public function email($sendAddres, $senderInfo, $receiveAddres, $receiverInfo, $emailTitle, $emailContent) {
//        $receiveAddres = '1047457825@QQ.com'; //定义收件人的邮箱
        $mail = new PHPMailer();
        $mail->isSMTP(); // 使用SMTP服务
        $mail->CharSet = "utf8"; // 编码格式为utf8，不设置编码的话，中文会出现乱码
        $mail->Host = "smtp.163.com"; // 发送方的SMTP服务器地址
        $mail->SMTPAuth = true; // 是否使用身份验证
        $mail->Username = "18401514128@163.com"; // 发送方的邮箱用户名，就是自己的邮箱名
        $mail->Password = "mey2910158"; // 发送方的邮箱密码，不是登录密码,是邮箱的第三方授权登录码,要自己去开启,在邮箱的设置->账户->POP3/IMAP/SMTP/Exchange/CardDAV/CalDAV服务 里面
        $mail->SMTPSecure = "ssl"; // 使用ssl协议方式,
        $mail->Port = 465; // QQ邮箱的ssl协议方式端口号是465/587

        $mail->setFrom($sendAddres, $senderInfo); // 设置发件人信息，如邮件格式说明中的发件人,
        $mail->addAddress($receiveAddres, $receiverInfo); // 设置收件人信息，如邮件格式说明中的收件人
        $mail->addReplyTo("xxxxx@qq.com", "Reply"); // 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
        //$mail->addCC("xxx@163.com")// 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址(这个人也能收到邮件)
        //$mail->addBCC("xxx@163.com");// 设置秘密抄送人(这个人也能收到邮件)
        //$mail->addAttachment("bug0.jpg");// 添加附件


        $mail->Subject = $emailTitle; // 邮件标题
        $mail->Body = $emailContent; // 邮件正文
        //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用

        if (!$mail->send()) {// 发送邮件
            echo "Message could not be sent.";
            echo "Mailer Error: " . $mail->ErrorInfo; // 输出错误信息
            $mes =msg(-1, '',$mail->ErrorInfo );
        } else {
            echo '发送成功';
            $mes = msg(200, $receiveAddres, '给'.$receiveAddres.'发送了一个邮件');
        }
        return json_encode($mes);
    }

}
