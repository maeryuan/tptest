<?php

namespace app\market\controller;

use think\Controller;

class WeiXin extends Controller {

    /**
     * 获取用户的openid
     */
    public function getBaseInfo() {
        //1.获取到code
        $appid = 'wxb8c0285d9618ed54';
        $redirect_uri = 'http://meywx.applinzi.com';
        $url = "https://opend.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . $redirect_uri . "&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
        header('location:' . $url);
    }

    function getUserOpenId() {
        $appid = 'wxb8c0285d9618ed54';
        $appsecret = '17ccc09cdda689c0929113b0795fcdea';
        $code = $_GET['code'];
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $appsecret . "&code=" . $code . "&grant_type=authorization_code";
        $res = http_curl($url, 'get');
        var_dump($res);
    }

    public function getWxAccessToken() {
        $appId = 'wxb8c0285d9618ed54';
        $appSecret = '17ccc09cdda689c0929113b0795fcdea'; //虚拟的，不要用
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appId . "&secret=" . $appSecret;
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $url); //要访问的地址 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //跳过证书验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        $data = json_decode(curl_exec($ch));
        if (curl_errno($ch)) {
            var_dump(curl_error($ch)); //若错误打印错误信息 
        }
        var_dump($data); //打印信息

        curl_close($ch); //关闭curl
    }

    function sendTemplateMsg() {
        //1.获取到accexx_token
        $access_token = $this->getWxAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=" . $access_token;
        //2.组装数组
        $arr = array(
            'touser' => 'openId',
            'template_id' => '	BpHjzoqBintBdOQkH4_6xxgx_eTki8x7zIg8DsHTDUk',
            'url' => 'www.baidu.com',
            'data' => array(
                'name' => array('value' => 'hello', 'color' => '#173177'),
                'money' => array('value' => 100, 'color' => '#173177'),
                'date' => array('value' => date('Y-m-d H:i:s'), 'color' => '#173177')
            )
        );
        //3.将数组转换json
        $postJson = json_encode($arr);
        //4.调用curl函数
       $res = $this->http_curl($url,'post','json',$postJson);
       var_dump($res);
    }

    public function http_curl($url, $type = 'get', $res = 'json', $arr = '') {
        /*
          $url 请求的url
          $type 请求类型
          $res 返回数据类型
          $arr post请求参数
         */
        $ch = curl_init();
        /* $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxf71d53c65df41aab&secret=e31e44c35067fb75759f53eed1cb1b26';  */
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if ($type == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        if ($res == 'json') {
            return json_decode($output, true);
        }
    }
}   