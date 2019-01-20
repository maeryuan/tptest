<?php

namespace app\weixin\controller;

use think\Controller;

class CodeKey extends Controller {
        //订阅号没用scope的权限，服务号没有认证也没用scope的权限
    public function getBaseInfo() {
        //1.获取到code
        $appid = 'wxb8c0285d9618ed54';
        $redirect_uri = urlencode('http://2.meywx.applinzi.com/index.php/CodeKey/getUserOpenId');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . $redirect_uri . "&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
        //2.获取网页授权的access_token
        //3.拉取用户的openid
        header('location:' . $url);
        
    }

    public function getUserOpenId() {
        //2.获取网页授权的access_token
        $appId = "wx2c8c81b5986a77c7";
        $appsecret = "972ef40c14ea7e8155a47c38f309a5ce";
        $code = $_GET['code'];
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appId . "&secret=" . $appsecret . "&code=" . $code . "&grant_type=authorization_code";
        //3.拉取用户的openid
        $res = $this->http_curl($url,'get');
        echo 'aaa';
        var_dump($res);        
    }
    
    public  function http_curl($url,$type='get',$res='json',$arr=''){
            /*
            $url 请求的url
            $type 请求类型
            $res 返回数据类型
            $arr post请求参数
             */
            $ch=curl_init();
            /*$url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxf71d53c65df41aab&secret=e31e44c35067fb75759f53eed1cb1b26';  */
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            if($type=='post'){
                curl_setopt($ch,CURLOPT_POST,1);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
            }
            $output = curl_exec($ch);
            curl_close($ch);
            if($res=='json'){
                return json_decode($output,true);
            }
    }

}
