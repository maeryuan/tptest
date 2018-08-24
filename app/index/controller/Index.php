<?php

namespace app\index\controller;

use think\Controller;

class Index extends Controller {
    
    public function index(){        
         return $this->fetch('index');
    }
    /**
     * 渲染注册页面
     * @return type
     */
    public function reg(){
        return $this->fetch('user/reg');
    }
    
     /**
     * 渲染登陆页面
     * @return type
     */
    public function login(){
        return $this->fetch('user/login');
    }
}
