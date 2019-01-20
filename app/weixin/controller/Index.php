<?php

namespace app\weixin\controller;

use think\Controller;

class Index extends Controller {

    public function index() {
        //1.将提么stamp，nonce，token按字典书序排序
        $timestamp = input('get.timestamp');
        $nonce = input('get.nonce');
        $token = 'weixin';
        $signature = input('get.signature');
        $array = array($timestamp, $nonce, $token);
        sort($array, SORT_STRING);
        //2.将排序后的三个参数拼接之后使用sha1加密
        $tmpstr = sha1(implode('', $array)); //join
        //3.将加密后的字符串与signature进行对比，判断给请求是否来自微信
        if ($tmpstr == $signature) {
            header('content-type:text');
            echo input('get.echostr');
            exit;
        }
    }

    public function test() {
//        header("Content-type:text/html;charset=utf-8");
        $signature = 'ddbad7e2fa5ef75a444d05b0863315e5b45fdb36';
        $echostr = '296612715644316671';
        $nonce = 1305297157;
        $token = 'weiphp';
        $timestamp = 1547816317;
        $arr = array($token, $nonce, $timestamp);
        sort($arr, SORT_STRING);
        $str2 = implode('', $arr);
        $tmpstr = sha1($str2);
        var_dump($tmpstr) . '<hr>';
        echo $signature;
    }

    public function testindex() {
//获得参数 signature nonce token timestamp echostr
        $nonce = $_GET['nonce'];
        $token = 'weiphp';
        $timestamp = $_GET['timestamp'];

        $signature = $_GET['signature'];
        //形成数组，然后按字典序排序

        $array = array($nonce, $timestamp, $token);
        sort($array, SORT_STRING);
        //拼接成字符串,sha1加密 ，然后与signature进行校验
        $str = sha1(implode('', $array));
        if ($str == $signature) {
            echo $_GET['echostr'];
            exit;
        } else {
            //1.获取到微信推送过来post数据（xml格式）
            $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
            //2.处理消息类型，并设置回复类型和内容
            /* <xml>
              <ToUserName><![CDATA[toUser]]></ToUserName>
              <FromUserName><![CDATA[FromUser]]></FromUserName>
              <CreateTime>123456789</CreateTime>
              <MsgType><![CDATA[event]]></MsgType>
              <Event><![CDATA[subscribe]]></Event>
              </xml> */
            $postObj = simplexml_load_string($postArr);
            //$postObj->ToUserName = '';
            //$postObj->FromUserName = '';
            //$postObj->CreateTime = '';
            //$postObj->MsgType = '';
            //$postObj->Event = '';
            // gh_e79a177814ed
            //判断该数据包是否是订阅的事件推送
            if (strtolower($postObj->MsgType) == 'event') {
                //如果是关注 subscribe 事件
                if (strtolower($postObj->Event == 'subscribe')) {
                    //回复用户消息(纯文本格式)
                    $toUser = $postObj->FromUserName;
                    $fromUser = $postObj->ToUserName;
                    $time = time();
                    $msgType = 'text';
                    $content = '欢迎关注我们的微信公众账号' . $postObj->FromUserName . '-' . $postObj->ToUserName;
                    $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                    $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                    echo $info;
                    /* <xml>
                      <ToUserName><![CDATA[toUser]]></ToUserName>
                      <FromUserName><![CDATA[fromUser]]></FromUserName>
                      <CreateTime>12345678</CreateTime>
                      <MsgType><![CDATA[text]]></MsgType>
                      <Content><![CDATA[你好]]></Content>
                      </xml> */
                }
            }
        }
    }

}
