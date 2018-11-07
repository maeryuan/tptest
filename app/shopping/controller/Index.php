<?php

namespace app\shopping\controller;
use think\Controller;

class Index extends Controller{
    
    public function index(){
//        if(!session('userinfo')){
//            return $this->error("请先登录",'shopping/Login/index');
//        }else{
//            return $this->fetch('index');
//        }
        return $this->fetch();
    }
    
    public function main(){
        $version = 'Apache/2.4.9';
        $this->assign('apache_version',$version);
        return $this->fetch('/main');
    }
}
