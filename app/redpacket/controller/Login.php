<?php

namespace app\redpacket\controller;

use think\Controller;
use app\redpacket\model\User;

class Login extends Controller {

    public function index() {

        return $this->fetch('login');
    }

    /**
     * 登陆操作
     */
    public function doLogin() {
        #判断提交表单方式
        if (request()->ispost()) {
            #获取表单提交数据
            $username = input('post.username');
            $password = input('post.password');
//            $code = input('post.code');
            $data = [
                'username' => $username,
                'password' => $password];
            #根据username,password查询数据库
            $user = User::get([
                        'username' => $username,
                        'password' => md5($password)
            ]);
            #判断查询结果
            if (!$user) {
                return json(msg(-2, '', '账号或密码错误'));
            }
            #开启session（username）user_id                       
            session('userName', $user->username);
            session('userId', $user->id);
            #跳转主页面
            return $this->redirect("redpacket/index/home");
        }
    }

    public function loginOut() {
        session('userName', null);
        session('userId', NULL);
        return $this->redirect("Index/index");
    }

}
