<?php

namespace app\redpacket\controller;
use think\Controller;

class Login extends Controller{
    public function index(){
       
        return $this->fetch('login');
    }
    
    public function doLogin(){
        #获取数据
        $data = input('param.');
        var_dump($data);
    }
}

