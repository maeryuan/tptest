<?php

namespace app\shopping\controller;
use think\Controller;

class Index extends Controller{
    //后台主页
    public function index(){
        //检测管理员是否登陆
        if(!session('userinfo')){
            //未登录，跳转到登陆页面
           return $this->redirect('login/index');
        }else{
            return $this->fetch('index');
        }

    }
    
    public function main(){
        $version = 'Apache/2.4.9';
        $this->assign('apache_version',$version);
        return $this->fetch('/main');
    }
}
